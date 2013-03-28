<?php defined('SYSPATH') or die('No direct script access.');

class RedisDB {
    
    private static $_instance = null;
    private static $_connection = null;
    protected $_config = array();

    private function __construct() {

    }
    
    public static function instance() {
        
        if (!RedisDB::$_instance) { 
            $config = Kohana::$config->load('redis.default');
            try {
                RedisDB::$_connection = new Redisent($config['dsn']);
                RedisDB::$_connection->select($config['database']);
                RedisDB::$_instance = new RedisDB();
            } catch (RedisException $e) {
                throw new RedisException($e->getMessage());
            }
        } 
        return RedisDB::$_instance; 
        
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

    public static function exists($key) {
        return self::$_connection->exists($key);
    }
    
    public static function expire($key, $time) {
        return self::$_connection->expire($key, $time);
    }
    
    public static function get($key) {
        return self::$_connection->get($key);
    }
    
    public static function getJSON($key) {
        return json_decode(self::$_connection->get($key), true);
    }
    
    public static function hget($key, $field) {
        return self::$_connection->hget($key, $field);
    }
    
    public static function hgetall($key) {
        return self::$_connection->hgetall($key);
    }
    
    public static function hkeys($key) {
        return self::$_connection->hkeys($key);
    }
    
    public static function hset($key, $field, $value) {
        return self::$_connection->hset($key, $field, $value);
    }
    
    public static function set($key, $value) {
        return self::$_connection->set($key, $value);
    }
    
    public static function setJSON($key, $value) {
        return self::$_connection->set($key, json_encode($value));
    }
    
    public static function del($key) {
        return self::$_connection->del($key);
    }
    
    public static function smembers($key) {
        return self::$_connection->smembers($key);
    }
    
    public static function sadd($key, $element) {
        return self::$_connection->sadd($key, $element);
    }
    
    public static function srem($key, $element) {
        return self::$_connection->srem($key, $element);
    }

    public static function llen($key) {
        return self::$_connection->llen($key);
    }

    public static function lpush($key, $value) {
        return self::$_connection->lpush($key, $value);
    }

    public static function lpop($key) {
        return self::$_connection->lpop($key);
    }
    
    public static function lrange($key, $from, $to) {
        return self::$_connection->lrange($key, $from, $to);
    }
    
    public static function rpush($key, $value) {
        return self::$_connection->rpush($key, $value);
    }
    
    public static function keys($pattern) {
        return self::$_connection->keys($pattern);
    }
    
    public static function incr($key) {
        return self::$_connection->incr($key);
    }
    
    
    
}

?>
