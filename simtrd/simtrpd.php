#!/usr/bin/php -q
<?php

/**
 * Demon przeliczający stan projektów
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
    'appName' => 'simtrd_projects',
    'appDir' => dirname(__FILE__),
    'appDescription' => 'Przelicza projekty',
    'authorName' => 'Magnus Nox',
    'authorEmail' => 'magnax@gmail.com',
    'sysMaxExecutionTime' => '0',
    'sysMaxInputTime' => '0',
    'sysMemoryLimit' => '1024M',
    'appRunAsGID' => 1000,
    'appRunAsUID' => 1000,
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

$runningOK = true;
$cnt = 1;

while(!System_Daemon::isDying() && $runningOK) {

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

        }
    }

    $end_time = microtime(true);

    System_Daemon::log('cnt_proj', 'Count: '.count($projects_ids).'; time: '.($end_time-$start_time));

    System_Daemon::iterate(SLEEP_TIME);

}

System_Daemon::stop();

?>
