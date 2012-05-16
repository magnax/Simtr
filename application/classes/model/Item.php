<?php

abstract class Model_Item {
    
    //datasource
    protected $source = null;
    
    //constants describing type of item
    const TOOL = 'tools';
    
    public function __construct($source) {
        $this->source = $source;
    }
    
    public static function getInstance($source) {
        if ($source instanceof Predis_Client) {
            return new Model_Redis_Item($source);
        }
    }
    
    abstract public function fetchAll($filter);
    
}

?>
