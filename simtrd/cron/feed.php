<?php 

/**
 * feeding characters
 */

define ('SYSPATH', '');

error_reporting(E_ALL);

/**
 * config file
 */
require_once '../config.php';

//mysql connection
$db = mysql_connect($config['db_server'], $config['db_username'], $config['db_password']) or die ('Cannot connect to MySQL');
mysql_select_db($config['db_name']);
//mysql_query("SET NAMES 'LATIN1'");

//redis init:
require_once '../../application/modules/redisent/classes/redisent.php';
require_once '../../application/modules/redisent/classes/redisexception.php';
require_once '../../application/classes/redisdb.php';
$redis = RedisDB::getInstance()
    ->connect($config['database_dsn']);

//build nourishing resources table (ID->EATEN_AMOUNT) sorted desc by EATEN_AMOUNT
$sql = 'select id, food, d as name from resources where food is not null order by food desc';
$query = mysql_query($sql);

$resources = array();
$names = array();

while ($resource = mysql_fetch_array($query)) {
    $resources[$resource['id']] = $resource['food'];
}

unset($sql);
unset($query);
unset($resource);

//print_r($resources);

$sql = 'select id, fed from characters where life>0';
$query = mysql_query($sql);

define ('PATH', $config['time_daemon_path']);

function getTime() {
    $output = shell_exec(PATH.' say');
    if (!$output) {
        $output = time() - 1292300000;
    } elseif (strpos($output, 'rror:')) {
        return null;
    }
    return str_replace("\n", '', $output);
}

$time = getTime();

while ($character = mysql_fetch_array($query)) {
    
    //default life descrease
    $life_decrease = 50;
    
    //pobierz listę surowców
    $raws = $redis->getJSON("raws:{$character['id']}");
    
    echo "{$character['id']}: {$character['fed']} - ";
    
    //default event
    $event_type = 'Hungry';
    
    if (!$raws) {
        echo "Zabieram $life_decrease punktów.\n";
        $character['fed'] -= 50;
    } else {
        echo "Ma jakies surowce.";
        $food_raws = array_intersect_key($resources, $raws);
        if (!count($food_raws)) {
            echo " Nie jedzenie. Zabieram $life_decrease punktów.\n";
            $character['fed'] -= 50;
        } else {
            $event_type = 'Eat';
            $fed = 0;
            //tablica food raws pokazuje jakie ma jedzenie i kolejność od
            //najcięższego
            foreach($food_raws as $k=>$v) {
                echo "Ma {$raws[$k]} of $k.";
                //ile potrzeba tego jedzenia
                $needed = ceil(($life_decrease - ($fed * $life_decrease))/50*$v);
                echo "Potrzeba $needed of $k, ";
                if ($raws[$k] >= $needed) {
                    $raws[$k] -= $needed;
                    $event_desc[$k] = $needed;
                    echo "$k => $needed. Najedzony.\n";
                    $fed = 1;
                    break;
                } else {
                    $fed = $raws[$k] / $needed;
                    $event_desc[$k] = $raws[$k];
                    echo "$k => {$raws[$k]}. Jeszcze nie najedzony.";
                    unset($raws[$k]);
                }
                
            }
            if ($fed < 1) {
                $life_decrease = $life_decrease - ($fed * $life_decrease);
                echo " Zabieram $life_decrease punktów.\n";
                $character['fed'] -= floor($life_decrease);
            } else {
                $character['fed'] += 50;
                if ($character['fed'] > 1000) {
                    $character['fed'] = 1000;
                }
            }
            echo "\n{$character['id']}: {$character['fed']}\n";
            print_r($raws);echo "\n";
            $redis->setJSON("raws:{$character['id']}", $raws);
        }
        
    }
    mysql_query("update characters set fed={$character['fed']} where id={$character['id']}");
    /**
     * @todo generate event (HUNGRY or EAT)
     */
    $event = array(
        'date'=>$time,
        'type'=>$event_type,
        'sndr'=>$character['id'],
    );
    switch ($event_type) {
        case 'Hungry':
            break;
        case 'Eat':
            $event['desc'] = json_encode($event_desc); //join(', ', $event_desc);
            break;
    }
    $serialised_event = json_encode($event);

    //zapisać samo zdarzenie:
    $event_id = $redis->incr('global:IDEvent');
    $redis->set("events:$event_id", $serialised_event);

    //przypisać event do postaci:
    $redis->lpush("characters:{$character['id']}:events", $event_id);
         
}

mysql_close();

?>