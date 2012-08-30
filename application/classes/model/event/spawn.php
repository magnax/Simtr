<?php

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
    
    public function dispatchArgs($event_data, $args, $character_id, $lang) {
        
        $returned = array();
        
        if (in_array('loc_type', $args)) {
            $returned['loc_type'] = ORM::factory('locationclass', $event_data['loc_type'])->name;
        }
        
        if (in_array('sndr', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['sndr'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['sndr'], $lang);
            }
            $returned['sndr'] = '<a href="chname?id='.
                $event_data['sndr'].'">'.$name.'</a>';
        }
        
        return $returned;
        
    }

    
    public function send() {}

}

?>