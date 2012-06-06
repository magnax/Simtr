<?php

abstract class Model_Dict {

    protected $lang = 'pl';

    protected $source;

    public function  __construct($source, $lang) {
        $this->source = $source;
        $this->lang = $lang;
    }

    public static function getInstance($source, $lang = 'pl') {

        //if ($source instanceof Redisent) {
        if ($source instanceof Redis) {
            return new Model_Dict_Redis($source, $lang);
        }

    }

    abstract function getString($str);

}

?>