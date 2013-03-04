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
    
    /**
     * identyfikator opuszczanej lokacji
     *
     * @var <type> int
     */
    protected $exit_location_id;

    public function setLocationId($location_id) {
        $this->location_id = $location_id;
    }
    
    public function setExitLocationId($location_id) {
        $this->exit_location_id = $location_id;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['locid'] = $this->location_id;
        $arr['exit_id'] = $this->exit_location_id;
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
            $lname = Utils::getLocationName($location_name);
            $returned['locid'] = '<a href="lname?id='.$event_data['locid'].'">'.$lname.'</a>';
        }
        
        if (in_array('exit_id', $args) && isset($event_data['exit_id'])) {
            $exit_location = ORM::factory('location')->where('id', '=', $event_data['exit_id'])
            ->find();
            if ($exit_location->parent_id) {
                $returned['exit_id'] = $exit_location->name;
            } else {
                $exit_location_name = ORM::factory('lname')->name($character_id, $event_data['exit_id'])->name;
                $lname = Utils::getLocationName($exit_location_name);
                $returned['exit_id'] = '<a href="lname?id='.$event_data['exit_id'].'">'.$lname.'</a>';
            }
        }
        
        return $returned;
        
    }    
    
    public function send() {}

}

?>
