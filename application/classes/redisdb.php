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
        
    }
    
    public static function get($key) {
        return json_decode(self::$_connection->get($key), true);
    }
    
    public static function set($key, $value) {
        self::$_connection->set($key, json_encode($value));
    }
    
    public static function smembers($key) {
        return self::$_connection->smembers($key);
    }
}

?>
