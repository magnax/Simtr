<?php

abstract class Model_Buildmenu {
    
    protected $source;
    protected $dict;
    
    private function  __construct($source, $dict) {
        $this->source = $source;
        $this->dict = $dict;
    }
    
    public static function getInstance($source, $dict) {
        if ($source instanceof Predis_Client) {
            return new Model_Buildmenu_Redis($source, $dict);
        }
    }

}

?>
