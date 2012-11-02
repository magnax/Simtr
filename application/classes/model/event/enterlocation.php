<?php

class Model_Event_EnterLocation extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia ENTER_LOCATION
     */

    /**
     * identyfikator nowej lokacji
     *
     * @var <type> int
     */
    protected $location_id;

    public function setLocationId($location_id) {
        $this->location_id = $location_id;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['locid'] = $this->location_id;
        $arr['sndr'] = $this->sender;

        return $arr;

    }

    public function dispatchArgs($event_data, $args, $character_id, $lang) {
        
        $location = ORM::factory('location')->where('id', '=', $event_data['locid'])
            ->find();
        
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['sndr'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['sndr'], $lang);
            }
            $returned['sndr'] = '<a href="chname?id='.
                $event_data['sndr'].'">'.$name.'</a>';
        }
        
        if ($location->parent_id) {
            $returned['locid'] = $location->name;
        } else {
            $location_name = ORM::factory('lname')->name($character_id, $event_data['locid'])->name;
            $lname = ($location_name) ? $location_name : 'unknown location';
            $returned['locid'] = '<a href="lname?id='.$event_data['locid'].'">'.$lname.'</a>';
        }
        
        return $returned;
        
    }    
    
    public function send() {}

}

?>
