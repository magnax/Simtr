#!/usr/bin/php -q
<?php

/**
 * Demon rozliczający zakończone projekty
 */

// Allowed arguments & their defaults
$runmode = array(
    'no-daemon' => false,
    'help' => false,
    'write-initd' => false,
);

// Scan command line attributes for allowed arguments
foreach ($argv as $k=>$arg) {
    if (substr($arg, 0, 2) == '--' && isset($runmode[substr($arg, 2)])) {
        $runmode[substr($arg, 2)] = true;
    }
}

// Help mode. Shows allowed argumentents and quit directly
if ($runmode['help'] == true) {
    echo 'Usage: '.$argv[0].' [runmode]' . "\n";
    echo 'Available runmodes:' . "\n";
    foreach ($runmode as $runmod=>$val) {
        echo ' --'.$runmod . "\n";
    }
    die();
}

require_once "System/Daemon.php";                 // Include the Class

define ('PATH', '/home/mn/simtrd/d.py');
define ('SLEEP_TIME', 20);

error_reporting(E_ALL);

$options = array(
    'appName' => 'simtrd-pf',
    'appDir' => dirname(__FILE__),
    'appDescription' => 'Rozlicza projekty',
    'authorName' => 'Magnus Nox',
    'authorEmail' => 'magnax@gmail.com',
    'sysMaxExecutionTime' => '0',
    'sysMaxInputTime' => '0',
    'sysMemoryLimit' => '1024M',
    'appRunAsGID' => 0,
    'appRunAsUID' => 0,
);

System_Daemon::setOptions($options);

if (!$runmode['no-daemon']) {
    // Spawn Daemon
    System_Daemon::start();
}

// With the runmode --write-initd, this program can automatically write a
// system startup file called: 'init.d'
// This will make sure your daemon will be started on reboot
if (!$runmode['write-initd']) {
    System_Daemon::info('not writing an init.d script this time');
} else {
    if (($initd_location = System_Daemon::writeAutoRun()) === false) {
        System_Daemon::notice('unable to write init.d script');
    } else {
        System_Daemon::info(
            'sucessfully written startup script: %s',
            $initd_location
        );
    }
}

$runningOK = true;
$cnt = 1;

//redis init:
require_once 'Predis.php';                     // Include database class
$redis = new Predis_Client(array(
    'host'     => '127.0.0.1',
    'port'     => 6379,
    'database' => 15,
    'alias' => 'mn'
));

try {
    $redis->select('mn');
} catch (Predis_CommunicationException $e) {
    System_Daemon::notice('Serwer Redis nie uruchomiony');
    $runningOK = FALSE;
}

function getTime() {
    $output = shell_exec(PATH.' say');
    if (!$output) {
        $output = time() - 1292300000;
    } elseif (strpos($output, 'rror:')) {
        return null;
    }
    return str_replace("\n", '', $output);
}

function strip(&$v) {
    $v = str_replace('finished_projects:', '', $v);
}

/**
 * deletes element from array (if exists)
 * 
 * @param <type> $ar array
 * @param <type> $el element to remove
 */
function deleteFromArray(&$ar, $el) {
   foreach ($ar as $k=>$v) {
       if ($v == $el) {
           unset($ar[$k]);
       }
   }
}

$places = array(
    'loc'=>'locations',
    'shp'=>'ships',
    'cab'=>'cabins',
    'veh'=>'vehicles',
    'bld'=>'buildings',
    'rom'=>'rooms'
);

define('EQ_MAX', 15000);

while(!System_Daemon::isDying() && $runningOK) {

    $time = getTime();

    $start_time = microtime(true);

    $projects_ids = $redis->keys('finished_projects:*');

    if (count($projects_ids)) {
        array_walk($projects_ids, 'strip');
        foreach ($projects_ids as $project_id) {
            
            $project = json_decode($redis->get("projects:$project_id"), true);
            $owner = json_decode($redis->get("characters:{$project['owner_id']}"), true);

            $loc_type_string = $places[$project['place_type']];

            $is_owner_present = ($owner['place_type']==$project['place_type'])
                && ($owner['place_id']==$project['place_id']);

            //dodanie surowca do inwentarza lub na ziemię
            if ($is_owner_present && $owner['eq_weight'] + $project['amount'] <= EQ_MAX) {
                $key = "characters:{$project['owner_id']}:equipment:raws";
                $owner['eq_weight'] += $project['amount'];
                $redis->set("characters:{$project['owner_id']}", json_encode($owner));
                $event_type = 'GetRawEnd';
            } else {
                $key = "$loc_type_string:{$project['place_id']}:raws";
                $event_type = 'GetRawEndGround';
            }
            //dodanie odpowiedniego surowca do eq lub na ziemię
            $raws = $redis->smembers($key);
            foreach ($raws as $i) {
                $eq_item = json_decode($i, TRUE);
                if (isset($eq_item[$project['resource_id']])) {
                    $eq_item[$project['resource_id']] += $project['amount'];
                }
                $redis->sadd($key, json_encode($eq_item));
            }

            //zwolnij wszystkich pracowników
            $num_workers = $redis->scard("projects:$project_id:workers");
            $workers = $redis->smembers("projects:$project_id:workers");
            //pusta tablica odbiorców eventu
            $event_recipients[] = array();
            foreach ($workers as $worker_id) {
                $worker = json_decode($redis->get("characters:$worker_id"), true);
                $worker['project_id'] = null;
                $redis->set("characters:$worker_id", json_encode($worker));
                //każdy worker musi dostać info o zakończeniu projektu
                $event_recipients[] = $worker_id;
            }
            $redis->del("projects:$project_id:workers");
            $redis->del("projects:$project_id:participants");

            //jeśli projekt zbierania surowców to zwolnij miejsce wydobycia
            if ($project['type_id'] == 'get_raw') {
                $location = json_decode($redis->get("locations:{$project['place_id']}"), true);
                $location['used_slots'] -= $num_workers;
                $redis->set("locations:{$project['place_id']}", json_encode($location));
            }
            
            $redis->del("finished_projects:$project_id");
            $loc_projects = $redis->smembers("$loc_type_string:{$project['place_id']}:projects");
            $redis->del("$loc_type_string:{$project['place_id']}:projects");
            foreach ($loc_projects as $p) {
                if ($p != $project_id) {
                    $redis->sadd("$loc_type_string:{$project['place_id']}:projects", $p);
                }
            }

            $resource = json_decode($redis->get("resources:{$project['res_id']}"), true);
            //dopisać event:
            $event = array(
                'date'=>$time,
                'type'=>$event_type,
                'name'=>$resource['type'], //@todo: ściągać z surowca czynność
                'sndr'=>$project['owner_id'],
                'res_id'=>$project['resource_id'], //@todo: ujednolicić res_id/resource_id
                'amount'=>$project['amount']
            );
            $serialised_event = json_encode($event);

            //zapisać samo zdarzenie:
            $event_id = $redis->incr('global:IDEvent');
            $redis->set("events:$event_id", $serialised_event);

            //jeśli trzeba, dodać inicjatora projektu
            if (!in_array($project['owner_id'], $event_recipients) && $is_owner_present) {
                $event_recipients[] = $project['owner_id'];
            }

            //przypisać event do wszystkich odbiorców:
            foreach ($event_recipients as $r) {
                $redis->lpush("characters:$r:events", $event_id);
            }

        }
    }

    $end_time = microtime(true);

    //notice tylko jeśli były projekty
    //if (count($projects_ids)) {
        System_Daemon::info('Count: '.count($projects_ids).'; time: '.($end_time-$start_time));
    //}

    System_Daemon::iterate(SLEEP_TIME);

}

System_Daemon::stop();

?>
