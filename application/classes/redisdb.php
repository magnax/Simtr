<?php defined('SYSPATH') or die('No direct script access.');

class RedisDB {
    
    private static $_instance = null;
    private static $_connection = null;

    private function __construct() {
        
    }
    
    public static function getInstance() {
        
        if (!self::$_instance) { 
            self::$_instance = new RedisDB(); 
        } 
        return self::$_instance; 
        
    }

    public function connect($dsn) {
        
        try {
            self::$_connection = new Redisent($dsn);
        } catch (RedisException $e) {
            throw new RedisException($e->getMessage());
        }
        
        return self::$_instance;
        
    }
    
    public function getConnectionObject() {
        return self::$_connection;
    }

    public static function get($key) {
        return json_decode(self::$_connection->get($key), true);
    }
    
    public static function set($key, $value) {
        return self::$_connection->set($key, json_encode($value));
    }
    
    public static function del($key) {
        return self::$_connection->del($key);
    }
    
    public static function smembers($key) {
        return self::$_connection->smembers($key);
    }
    
    public static function llen($key) {
        return self::$_connection->llen($key);
    }
    
    public static function lrange($key, $from, $to) {
        return self::$_connection->lrange($key, $from, $to);
    }
    
}

?>
