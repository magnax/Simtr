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
    
    public function dispatchArgs($event_data, $args, $character) {
        
        $returned = array();
        
        if (in_array('loc_type', $args)) {
            $returned['loc_type'] = Model_Dict::getInstance($this->source)->
                getString($event_data['loc_type']);
        }
        
        if (in_array('sndr', $args)) {
            $name = $character->getChname($event_data['sndr']);
            if (!$name) {
                $name = $character->getUnknownName($event_data['sndr']);
                $name = Model_Dict::getInstance($this->source)->getString($name);
            }
            $returned['sndr'] = html::anchor('user/char/nameform/'.$event_data['sndr'], $name);
        }
        
        return $returned;
        
    }

    
    public function send() {}

}

?>