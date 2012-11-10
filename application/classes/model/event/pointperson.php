<?php

class Model_Event_PointPerson extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia POINT_PERSON
     */
    protected $recipient;

    public function setRecipient($ch) {
        $this->recipient = $ch;
    }
    
    public function toArray() {

        $arr = parent::toArray();

        $arr['sndr'] = $this->sender;
        $arr['rcpt'] = $this->recipient;

        return $arr;

    }

    public function dispatchArgs($event_data, $args, $character_id, $lang) {
        
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['sndr'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['sndr'], $lang);
            }
            $returned['sndr'] = '<a href="chname?id='.
                $event_data['sndr'].'">'.$name.'</a>';
        }
        
        if (in_array('rcpt', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['rcpt'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['rcpt'], $lang);
            }
            $returned['rcpt'] = '<a href="chname?id='.
                $event_data['rcpt'].'">'.$name.'</a>';
        }
        
        return $returned;
        
    }
    
    public function send() {}

}

?>

