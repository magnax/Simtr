#! /usr/bin/env php

<?php

/**
 * Demon rozliczający zakończone projekty
 */

require_once "System/Daemon.php";                 // Include the Class
//
//version of this file
define('VER', '0.0.3');

/**
 * config file from framework
 */
require_once 'config.php';

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

define ('PATH', $config['time_daemon_path']);
define ('SLEEP_TIME', 20);
define ('SYSPATH', '');

error_reporting(E_ALL);

$options = array(
    'appName' => 'simtrd-pf',
    'appDir' => dirname(__FILE__),
    'appDescription' => 'Rozlicza zakonczone projekty',
    'authorName' => 'Magnus Nox',
    'authorEmail' => 'magnax@gmail.com',
    'sysMaxExecutionTime' => '0',
    'sysMaxInputTime' => '0',
    'sysMemoryLimit' => '1024M',
    'appRunAsGID' => $config['gid'],
    'appRunAsUID' => $config['uid'],
    'logLocation' => $config['finished_log_file'],
    'appPidLocation' => $config['finished_pid_file'],
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
            VER.' sucessfully written startup script: %s',
            $initd_location
        );
    }
}

//redis init:
require_once '../application/modules/redisent/classes/redisent.php';
require_once '../application/modules/redisent/classes/redisexception.php';
require_once '../application/classes/redisdb.php';
$redis = RedisDB::getInstance()
    ->connect($config['database_dsn']);
//    ->getConnectionObject();

//mysql connection
$db = mysql_connect($config['db_server'], $config['db_username'], $config['db_password']) or die ('Cannot connect to MySQL');
mysql_select_db($config['db_name']);

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

$runningOK = true;
$cnt = 1;

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
            
            System_Daemon::info(microtime(true).': rozliczam: '.$project_id);
            
            $project = json_decode($redis->get("projects:$project_id"), true);
            
            //project initiator (owner)
            $query = mysql_query("select * from characters where id={$project['owner_id']}");
            $owner = mysql_fetch_array($query);

            $is_owner_present = ($owner['location_id'] == $project['place_id']);

            print_r($owner);
            echo "Owner present: $is_owner_present\n";

            //dodanie surowca do inwentarza lub na ziemię
            if ($is_owner_present) {
                
                $owner_weight = 0;
                $owner_has_resource = false;

                $raws = $redis->getJSON("raws:{$owner['id']}");
                if ($raws) {
                    foreach ($raws as $k => $raw) {
                        $owner_weight += $raw;
                        if ($k == $project['resource_id']) {
                            $owner_has_resource = true;
                        }
                    }
                }
                /**
                 * @todo add items weight count (key: items:CHAR_ID
                 */
                
                echo "Owner weight: $owner_weight, has resource: $owner_has_resource\n";
                
                if (($owner_weight + $project['amount']) <= EQ_MAX) {
                    //dodaj do ekwipunku
                    $event_type = 'GetRawEnd';
                    if ($owner_has_resource) {
                        //dodać do istniejącego surowca
                        $raws[$project['resource_id']] += $project['amount'];
                    } else {
                        //utworzyć nowy surowiec
                        $raws[$project['resource_id']] = $project['amount'];
                    }
                    $redis->setJSON("raws:{$owner['id']}", $raws);
                } else {
                    //połóż na ziemię
                    $event_type = 'GetRawEndGround';
                    
                }
                
            } else {
                //nieobecny inicjator projektu, na ziemię
                $event_type = 'GetRawEndGround';
                
            }
            
            echo "Event: $event_type\n";
            
//            if ($raws) {
//                $raws = json_decode($raws, true);
//                if (in_array($project['resource_id'], array_keys($raws))) {
//                    $raws[$project['resource_id']] += $project['amount'];
//                } else {
//                    $raws[$project['resource_id']] = $project['amount'];
//                }
//            } else {
//                $raws[$project['resource_id']] = $project['amount'];
//            }

//            System_Daemon::info('Raws: '.json_encode($raws));

            //zwolnij wszystkich pracowników
            
            /**
             * workers - zbiór wszystkich aktualnie pracujących przy projekcie
             *  powinni oni dostać powiadomienie o zakończeniu
             * 
             * participants - zbiór wszystkich uczestników projektu w postaci:
             *   id, start, end, factor
             * 
             *   - id       - id postaci,
             *   - start    - czas rozpocz. pracy,
             *   - end      - czas zakończenia pracy lub null jeśli pracuje,
             *   - faktor   - współczynnik przeliczania czasu
             */
            
            //pobierz tablicę pracowników
            $workers = $redis->getJSON("projects:$project_id:workers");
            $num_workers = count($workers);
            //pusta tablica odbiorców eventu
            $event_recipients[] = array();
            foreach ($workers as $worker_id) {
                //każdy worker musi dostać info o zakończeniu projektu
                $event_recipients[] = $worker_id;
                //usunąć klucz dla bieżącego projektu pracowników
                $redis->del("characters:$worker_id:current_project");
            }
            
            //usuń tablicę pracowników projektu
            $redis->del("projects:$project_id:workers");
            
            //usuń tablicę czasów pracy nad projektem
            $redis->del("projects:$project_id:participants");
            
            //usuń projekt z listy zakończonych 
            $redis->del("finished_projects:$project_id");
            
            //usuń projekt z listy projektów lokacji
            $redis->srem("locations:{$project['place_id']}:projects", $project_id);

//            $resource = json_decode($redis->get("resources:{$project['res_id']}"), true);
            //dopisać event:
            $event = array(
                'date'=>$time,
                'type'=>$event_type,
                'name'=>$project['name'],
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
    if (count($projects_ids)) {
        System_Daemon::info('Count: '.count($projects_ids).'; time: '.($end_time-$start_time));
    }

    System_Daemon::iterate(SLEEP_TIME);

}

System_Daemon::stop();

?>