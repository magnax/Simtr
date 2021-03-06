#! /usr/bin/env php

<?php

require_once "System/Daemon.php";                 // Include the Class

//version of this file
define('VER', '0.0.2');

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
define ('SLEEP_TIME', 3);
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
    'appRunAsGID' => $config['gid'],
    'appRunAsUID' => $config['uid'],
    'logLocation' => $config['projects_log_file'],
    'appPidLocation' => $config['projects_pid_file'],
);

System_Daemon::setOptions($options);

if (!$runmode['no-daemon']) {
    // Spawn Daemon
    System_Daemon::start();
    System_Daemon::info('VERSION: '.VER);
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

unset($runmode);

//redis init:
require_once '../application/modules/redisent/classes/redisent.php';
require_once '../application/modules/redisent/classes/redisexception.php';

try {
    $redis = new Redisent($config['database_dsn']);
} catch (RedisException $e) {
    throw new RedisException($e->getMessage());
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

function load_project($redis, $project_id) {
    $keys = $redis->hgetall("Project:$project_id");
    $project = array('id' => $project_id);
    $l = count($keys);
    while (count($keys)) {
        $key = array_shift($keys);
        $val = array_shift($keys);
        $project[$key] = $val;
    }
    return $project;
}

function save_project($redis, array $project) {
    $redis_key = "Project:{$project['id']}";
    unset($project['id']);
    foreach (array_keys($project) as $key) {
        $redis->hset($redis_key, $key, $project[$key]);
    }
}

$runningOK = true;

while(!System_Daemon::isDying() && $runningOK) {

    $time = getTime();

    $start_time = microtime(true);

    $projects_ids = $redis->keys('active_projects:*');

    if (count($projects_ids)) {
        System_Daemon::info('Projects count: ' . count($projects_ids));
        array_walk($projects_ids, 'strip');
        foreach ($projects_ids as $project_id) {
            System_Daemon::info('Uaktualniam: '.$project_id);
            //$project = json_decode($redis->get("projects:$project_id"), true);
            $project = load_project($redis, $project_id);
            $elapsed = 0;

            $participants = json_decode($redis->get("projects:$project_id:participants"), true);
            foreach ($participants as $p) {
                if ($p['end']) {
                    $elapsed += ($p['end'] - $p['start']) * $p['factor'];
                } else {
                    $elapsed += ($time - $p['start']) * $p['factor'];
                }
            }
            unset($participants);
            if ($elapsed >= $project['time']) {
                $project['time_elapsed'] = $project['time'];
                //dodaj projekt do rozliczenia
                $redis->set("finished_projects:$project_id", 1);
                //usuń projekt z aktywnych
                $redis->del("active_projects:$project_id");
            } else {
                $project['time_elapsed'] = $elapsed;
            }
            //$redis->set("projects:$project_id", json_encode($project));
            //$project->save();
            save_project($redis, $project);
            unset($elapsed);
        }
    }

    $end_time = microtime(true);

    System_Daemon::info('Count: '.count($projects_ids).'; time: '.($end_time-$start_time));

    System_Daemon::iterate(SLEEP_TIME);

}

System_Daemon::stop();

?>
