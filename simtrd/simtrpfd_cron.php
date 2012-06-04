<?php

/**
 * Skrypt rozliczający zakończone projekty
 */


define ('PATH', '/usr/local/lib/simtr/d.py');
//define ('PATH', 'python /home/magnax/domains/magnax.pl/public_html/game/simtrd/d.py');

error_reporting(E_ALL);

//redis init:
require_once 'Predis.php';                     // Include database class

//$redis = new Predis_Client('redis://magnax:4cd3a93c90d60288117ec4cadf8c0aaa@50.30.35.9:2693/?password=4cd3a93c90d60288117ec4cadf8c0aaa');

$redis = new Predis_Client(array(
    'host'     => '127.0.0.1',
    'port'     => 6379,
    'database' => 0
));

try {
    $redis->dbsize();
} catch (Exception $e) {
    echo('Serwer Redis nie uruchomiony');
    return false;
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
define('CRLF', "\n");


    $time = getTime();

    $start_time = microtime(true);

    $projects_ids = $redis->keys('finished_projects:*');

    if (count($projects_ids)) {
        echo count($projects_ids).CRLF;
        array_walk($projects_ids, 'strip');
        foreach ($projects_ids as $project_id) {
            echo $project_id.CRLF;
            $project = json_decode($redis->get("projects:$project_id"), true);
            $owner = json_decode($redis->get("characters:{$project['owner_id']}"), true);

            $loc_type_string = $places[$project['place_type']];

            $is_owner_present = ($owner['place_type']==$project['place_type'])
                && ($owner['place_id']==$project['place_id']);

            //dodanie surowca do inwentarza lub na ziemię
            if ($is_owner_present && $owner['eq_weight'] + $project['amount'] <= EQ_MAX) {
                $key = "raws:{$project['owner_id']}";
                $owner['eq_weight'] += $project['amount'];
                $redis->set("characters:{$project['owner_id']}", json_encode($owner));
                $event_type = 'GetRawEnd';
            } else {
                $key = "$loc_type_string:raws{$project['place_id']}";
                $event_type = 'GetRawEndGround';
            }
            //dodanie odpowiedniego surowca do eq lub na ziemię
            $raws = $redis->get($key);
            
            if ($raws) {
                $raws = json_decode($raws, true);
                if (in_array($project['resource_id'], array_keys($raws))) {
                    $raws[$project['resource_id']] += $project['amount'];
                } else {
                    $raws[$project['resource_id']] = $project['amount'];
                }
            } else {
                $raws[$project['resource_id']] = $project['amount'];
            }
            echo json_encode($raws).CRLF;

            $redis->set($key, json_encode($raws));

            //zwolnij wszystkich pracowników
            $workers = json_decode($redis->get("projects:$project_id:workers"), true);
            $num_workers = count($workers);
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
            if ($project['type_id'] == 'GetRaw') {
                $location = json_decode($redis->get("locations:{$project['place_id']}"), true);
                $location['used_slots'] -= $num_workers;
                $redis->set("locations:{$project['place_id']}", json_encode($location));
            }
            
            //usunąć projekt z zakończonych
            $redis->del("finished_projects:$project_id");
            
            //usunąć projekt z projektów w danej lokacji
            $redis->srem("$loc_type_string:{$project['place_id']}:projects", $project_id);
//            $loc_projects = $redis->smembers("$loc_type_string:{$project['place_id']}:projects");
//            $redis->del("$loc_type_string:{$project['place_id']}:projects");
//            foreach ($loc_projects as $p) {
//                if ($p != $project_id) {
//                    $redis->sadd("$loc_type_string:{$project['place_id']}:projects", $p);
//                }
//            }

            $resource = json_decode($redis->get("resources:{$project['res_id']}"), true);
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


?>
