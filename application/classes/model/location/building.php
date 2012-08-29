<?php

class Model_Location_Building extends Model_Location {
    
    public function __construct($data) {
        parent::__construct($data);
    }


    public function toArray() {
        return array_merge(
            parent::toArray(), 
            array(

            )
        );
    }
    
    public function getAllHearableCharacters($as_array = false) {
        return;
    }
    
    public function getAllVisibleCharacters($as_array = false) {
        return;
    }
    
    public function save() {
        return;
    }
    
}

?>
