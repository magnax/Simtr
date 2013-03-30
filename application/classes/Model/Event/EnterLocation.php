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
        
        return;
        
    }    
    
    public function send() {}

}

?>
