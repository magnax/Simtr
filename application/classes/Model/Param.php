<?php defined('SYSPATH') or die('No direct script access.');

class Model_Param extends OHM {
    
    protected $_columns = array('name', 'value', 'event_id');
    
    protected $_belongs_to = array('event');


    public static function getParams($event_id) {
        $params = RedisDB::smembers("Param:indices:event_id:{$event_id}");
        $returned = array();
        foreach ($params as $p) {
            $returned[RedisDB::hget("Param:$p", 'name')] =
                RedisDB::hget("Param:$p", 'value');
        }
        return $returned;
    }
    
    public static function dispatch(array $params, array $args, $character) {
        
        $returned = array();
        //make arguments in proper order and set initial values
        foreach ($args as $key) {
            $returned[$key] = null;
        }
        foreach ($params as $k => $v) {
            if (method_exists('Model_Param', $k)) {
                $returned[$k] = Model_Param::$k($v, $character);
            } else {
                $returned[$k] = $v;
            }
        }
        return $returned;
    }
    
    private static function sndr($id, $character) {
        return $character->getChNameLink($id);
    }
    
    private static function rcpt($id, $character) {
        return $character->getChNameLink($id);
    }
    
    private static function locid($id, $character) {
        return self::_location_link($id, $character);
    }
    
    private static function exit_id($id, $character) {
        return self::_location_link($id, $character);
    }
    
    private static function _location_link($id, $character) {
        $location = ORM::factory('Location')->where('id', '=', $id)
            ->find();
        
        if ($location->parent_id) {
            return $location->name;
        } else {
            $location_name = ORM::factory('LName')->name($character->id, $id)->name;
            $lname = Utils::getLocationName($location_name);
            return '<a href="lname?id='.$id.'">'.$lname.'</a>';
        }
    }
    
    private static function res_id($id, $character) {
        return ORM::factory('Resource', $id)->d;
    }
     
    private static function item_id($id) {
        return ORM::factory('Item', $id)->itemtype->name;
    }
    
    private static function location_type($id) {
        return ORM::factory('LocationClass', $id)->name;
    }
    
    private static function item_points($points) {
        
        $states = array(
            'm' => array(
                'zużyty', 'często używany', 'używany', 'nowy', 'całkiem nowy'
            ),
            'k' => array(
                'zużyta', 'często używana', 'używana', 'nowa', 'całkiem nowa'
            )
        );
        
        $type = strtolower($points[0]);
        $points = intval(substr($points,1));
        
        if (!in_array($type, array('m', 'k', 'n')) || $points < 0  || $points > 100) {
            return '[nieprawidłowy]';
        }
        
        if ($points > 80) {
            return $states[$type][4];
        } elseif ($points > 60) {
            return $states[$type][3];
        } elseif ($points > 40) {
            return $states[$type][2];
        } elseif ($points > 20) {
            return $states[$type][1];
        } else {
            return $states[$type][0];
        }
    }
}

?>