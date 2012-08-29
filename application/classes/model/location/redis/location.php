<?php

class Model_Location_Redis_Location extends Model_Location {
    
    public function fetchOne($location_id) {
        $data = RedisDB::get("locations:$location_id");
        return new Model_LocationFactory('Redis', $data);
    }
    
    public function save() {
        
    }
    
    public function getAllHearableCharacters($as_array = false) {
        
    }
    
}

?>
