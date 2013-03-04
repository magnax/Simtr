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

    public function dispatchArgs(array $args, Model_Character $character, $lang) {
        
        $location = ORM::factory('Location')->where('id', '=', $this->locid)
            ->find();
        
        $returned = parent::dispatchArgs($args, $character, $lang);
        
        if ($location->parent_id) {
            $returned['locid'] = $location->name;
        } else {
            $location_name = ORM::factory('LName')->name($character->id, $this->locid)->name;
            $lname = Utils::getLocationName($location_name);
            $returned['locid'] = '<a href="lname?id='.$this->locid.'">'.$lname.'</a>';
        }
        
        if (in_array('exit_id', $args) && isset($this->exit_id)) {
            $exit_location = ORM::factory('Location')->where('id', '=', $this->exit_id)
            ->find();
            if ($exit_location->parent_id) {
                $returned['exit_id'] = $exit_location->name;
            } else {
                $exit_location_name = ORM::factory('LName')->name($character->id, $this->exit_id)->name;
                $lname = Utils::getLocationName($exit_location_name);
                $returned['exit_id'] = '<a href="lname?id='.$this->exit_id.'">'.$lname.'</a>';
            }
        }
        
        return $returned;
        
    }    
    
    public function send() {}

}

?>
