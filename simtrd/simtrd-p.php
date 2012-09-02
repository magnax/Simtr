#!/usr/bin/php

<?php

/**
 * Demon przeliczający stan projektów
 */

require_once "System/Daemon.php";                 // Include the Class

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
define ('SLEEP_TIME', 1);
define ('SYSPATH', '');

error_reporting(E_ALL);

$options = array(
    'appName' => 'simtrd-p',
    'appDir' => dirname(__FILE__),
    'appDescription' => 'Przelicza projekty',
    'authorName' => 'Magnus Nox',
    'authorEmail' => 'magnax@gmail.com',
    'sysMaxExecutionTime' => '0',
    'sysMaxInputTime' => '0',
    'sysMemoryLimit' => '1024M',
    'appRunAsGID' => 0,
    'appRunAsUID' => 0,
    'logLocation' => $config['projects_log_file'],
    'appPidLocation' => $config['projects_pid_file'],
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
require_once '../application/modules/redisent/classes/redisent.php';
require_once '../application/classes/redisdb.php';
$redis = RedisDB::getInstance()
    ->connect($config['database_dsn'])
    ->getConnectionObject();

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
        System_Daemon::info('Projects count: ' . count($projects_ids));
        array_walk($projects_ids, 'strip');
        foreach ($projects_ids as $project_id) {
            System_Daemon::info('Uaktualniam: '.$project_id);
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

    System_Daemon::info('Count: '.count($projects_ids).'; time: '.($end_time-$start_time));

    System_Daemon::iterate(SLEEP_TIME);

}

System_Daemon::stop();

?>
