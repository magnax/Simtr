<?php

abstract class Model_ItemType {
    
    protected $source;
    
    public function __construct($source) {
        $this->source = $source;
    }
    
    static public function getInstance($source) {
        if ($source instanceof Redis) {
            return new Model_ItemType_Redis($source);
        }
    }
    
    public function getState($percent) {
        if ($percent > 0.8) return 'brand_new';
        elseif ($percent > 0.6) return 'new';
        elseif ($percent > 0.4) return 'used';
        elseif ($percent > 0.2) return 'often_used';
        else return 'crumbling';
    }

    abstract public function getName($item_id);
    
}

?>
