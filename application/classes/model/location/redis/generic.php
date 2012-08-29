<?php

class Model_Location_Redis_Generic extends Model_Location {

    public function fetchOne($location_id) {
        $data = json_decode($this->source->get("locations:$location_id"), true);
        return Model_LocationFactory::getInstance($this->source, $data);
    }
    
    public function getAllHearableCharacters($as_array = false) {
        
    }
    
    public function getAllVisibleCharacters($as_array = false) {
        
    }
    
    public function save() {
        
    }
    
}

?>
