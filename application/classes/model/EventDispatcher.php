<?php

abstract class Model_EventDispatcher {

    protected $id_character;

    protected $source;
    
    public function  __construct($source) {
        $this->source = $source;
    }

    public function setIdCharacter($id) {
        $this->id_character = $id;
    }

    public static function getInstance($source) {
        if ($source instanceof Predis_Client) {
            return new Model_EventDispatcher_Redis($source);
        }
    }

}

?>
