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
     
    private static function loc_type($id) {
        return ORM::factory('LocationClass', $id)->name;
    }
}

?>
