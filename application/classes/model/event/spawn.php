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
    
    public function dispatchArgs($event_data, $args, $character_id) {
        
        $dict = Model_Dict::getInstance($this->source);
        $chname = Model_ChNames::getInstance($this->source, $dict);
        
        $returned = array();
        
        if (in_array('loc_type', $args)) {
            $returned['loc_type'] = Model_Dict::getInstance($this->source)->
                getString($event_data['loc_type']);
        }
        
        if (in_array('sndr', $args)) {
            $returned['sndr'] = html::anchor('u/char/nameform/'.$event_data['sndr'], 
                $chname->getName($character_id, $event_data['sndr']));
        }
        
        return $returned;
        
    }

    
    public function send() {}

}

?>