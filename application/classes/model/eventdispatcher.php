<?php

abstract class Model_EventDispatcher {

    protected $id_character;

    protected $source;
    
    protected $lang = 'pl';


    public function  __construct($source, $lang) {
        $this->source = $source;
        $this->lang = $lang;
    }

    public function setIdCharacter($id) {
        $this->id_character = $id;
    }

    public static function getInstance($source, $lang) {
        //if ($source instanceof Redisent) {
        if ($source instanceof Redisent) {
            return new Model_EventDispatcher_Redis($source, $lang);
        }
    }

}

?>
