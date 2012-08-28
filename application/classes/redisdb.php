<?php

class RedisDB {
    private $_connection;
    protected $class_string = 'Redis';

    function __construct() {
        $this->_connection = new Redis();
        $this->_connection->connect('127.0.0.1');
    }
    
    public function values(array $values, array $expected = NULL) {
        
    }
    
    function get($key) {
        return json_decode($this->_connection->get($key), true);
    }
    function set($key, $value) {
        $this->_connection->set($key, json_encode($value));
    }
    public static function smembers($key) {
        return $this->_connection->smembers($key);
    }
}

?>
