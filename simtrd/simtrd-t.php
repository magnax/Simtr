#! /usr/bin/env php

<?php

/**
 * simtrd-t.php
 * 
 * Daemon process to updating travel
 */

require_once "System/Daemon.php";                 // Include the Class

//version of this file
define('VER', '0.0.1');

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
define ('SLEEP_TIME', 10);
define ('SYSPATH', '');

error_reporting(E_ALL);

$options = array(
    'appName' => 'simtrd-t',
    'appDir' => dirname(__FILE__),
    'appDescription' => 'Przelicza podroze',
    'authorName' => 'Magnus Nox',
    'authorEmail' => 'magnax@gmail.com',
    'sysMaxExecutionTime' => '0',
    'sysMaxInputTime' => '0',
    'sysMemoryLimit' => '1024M',
    'appRunAsGID' => $config['gid'],
    'appRunAsUID' => $config['uid'],
    'logLocation' => $config['travels_log_file'],
    'appPidLocation' => $config['travels_pid_file'],
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
    $v = str_replace('active_projects:', '', $v);
}

function load_position($redis, $position_id) {
    $keys = $redis->hgetall("Position:$position_id");
    $position = array('id' => $position_id);
    $l = count($keys);
    while (count($keys)) {
        $key = array_shift($keys);
        $val = array_shift($keys);
        $position[$key] = $val;
    }
    return $position;
}

function save_position($redis, array $position) {
    $redis_key = "Position:{$position['id']}";
    unset($position['id']);
    foreach (array_keys($position) as $key) {
        $redis->hset($redis_key, $key, $position[$key]);
    }
}

function distance($x1, $y1, $x2, $y2) {
    return sqrt(pow(abs($x2-$x1), 2)+pow(abs($y2-$y1), 2));
}

$runningOK = true;

while(!System_Daemon::isDying() && $runningOK) {

    $time = getTime();

    $start_time = microtime(true);
    
    //get all travelling characters
    $query = mysql_query("SELECT ch.id, location_id FROM characters ch
        left join locations l 
        on l.id = ch.location_id
        WHERE l.locationtype_id = 3");
    $c = mysql_num_rows($query);
    
    while ($character = mysql_fetch_array($query)) {

        echo $character['id']."\n";
        $position = load_position($redis, $redis->get("characters:{$character['id']}:position"));
        
        print_r($position);
    
        $t = ($time - $position['time']) * $position['speed'];
        $angle = deg2rad($position['dir']);
        
        echo $angle."\n";

        $position['x'] = $position['x'] + ($t * sin($angle));
        $position['y'] = $position['y'] + ($t * cos($angle));       
        $position['time'] = $time;

        echo "x: {$position['x']}, y: {$position['y']}\n";
        
        $progress = distance($position['x'], $position['y'], $position['x1'], $position['y1']);
        $dist = distance($position['x1'], $position['y1'], $position['x2'], $position['y2']);
        
        if ($progress >= $dist) {
            //get to destination
            
            //get location from position
            $loc_nr = $position['dest'];
            $key = 'location_'.$loc_nr.'_id';
            $query1 = mysql_query("select {$key} from roads where location_id = {$character['location_id']}");
            $location = mysql_fetch_array($query1);
            $id = $location[$key];
            //update character's location
            $query2 = mysql_query("update characters set location_id = $id where id = {$character['id']}");
            
            //delete character's position
            $redis->del("characters:{$character['id']}:position");
            
        }
        
        save_position($redis, $position);
        
        print_r($position);
        
    }
    
    $end_time = microtime(true);

    System_Daemon::info('Count: '.$c.'; time: '.($end_time-$start_time));

    System_Daemon::iterate(SLEEP_TIME);

}

System_Daemon::stop();

?>
