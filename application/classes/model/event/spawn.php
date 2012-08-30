<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event_Spawn extends Model_Event {

    protected $loc_type;
    
    public function setLocationType($type) {
        $this->loc_type = $type;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['loc_type'] = $this->loc_type;

        return $arr;

    }

}

?>