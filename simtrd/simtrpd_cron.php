
<?php

/**
 * Skrypt (do crona) przeliczający stan projektów
 */

define ('PATH', 'python /home/magnax/domains/magnax.pl/public_html/game/simtrd/d.py');

error_reporting(E_ALL);

//redis init:
require_once 'Predis.php';                     // Include database class
$redis = new Predis_Client('redis://magnax:4cd3a93c90d60288117ec4cadf8c0aaa@50.30.35.9:2693/?password=4cd3a93c90d60288117ec4cadf8c0aaa');

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
    $v = str_replace('active_projects:', '', $v);
}

$time = getTime();

$start_time = microtime(true);

$projects_ids = $redis->keys('active_projects:*');

if (count($projects_ids)) {
    array_walk($projects_ids, 'strip');
    foreach ($projects_ids as $project_id) {
        $project = json_decode($redis->get("projects:$project_id"), true);

        $elapsed = 0;

        $participants = json_decode($redis->get("projects:$project_id:participants"), true);
        foreach ($participants as $p) {
            if ($p['end']) {
                $elapsed += ($p['end'] - $p['start']) * $p['factor'];
            } else {
                $elapsed += ($time - $p['start']) * $p['factor'];
            }
        }
        if ($elapsed >= $project['time']) {
            $project['time_elapsed'] = $project['time'];
            //dodaj projekt do rozliczenia
            $redis->set("finished_projects:$project_id", 1);
            //usuń projekt z aktywnych
            $redis->del("active_projects:$project_id");
        } else {
            $project['time_elapsed'] = $elapsed;
        }
        $redis->set("projects:$project_id", json_encode($project));
        echo "{$project['id']}. {$project['time_elapsed']} / {$project['time']}\n";

    }
}

$end_time = microtime(true);


?>
