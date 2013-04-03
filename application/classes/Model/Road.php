<?php defined('SYSPATH') or die('No direct script access.');

class Model_Road extends ORM {   
    
    public $levels = array(
        '0' => 'ścieżka',
        '1' => 'piaszczysta',
        '2' => 'brukowana',
        '3' => 'szosa',
        '4' => 'autostrada',
    );

    public function get_level_name() {
        return $this->levels[$this->level];
    }

    public function getDistance() {
        return $this->distance;
    }
    
    public function getDirection() {
        return $this->direction;
    }
    
    public function getDestinationLocationID() {
        return $this->end_location_id;
    }
    
    public function get_end($start_id) {
        return ($this->location_1_id == $start_id) ? $this->location_2_id : $this->location_1_id;
    }
}

?>
