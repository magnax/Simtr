<?php

class Model_Event_PointExit extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia POINT_EXIT
     */

    /**
     * identyfikator drogi
     *
     * @var <type> int
     */
    protected $exit_id;

    public function setExit($exit_id) {
        $this->exit_id = $exit_id;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['exit_id'] = $this->exit_id;

        return $arr;

    }

    public function dispatchArgs($event_data, $args, $character) {
    
        $dict = Model_Dict::getInstance($this->source);
        
        $res = Model_Road::getInstance($this->source)->findOneByID($event_data['exit_id']);
            
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $name = $character->getChname($event_data['sndr']);
            $returned['sndr'] = '<a href="/user/char/nameform/'.
                $event_data['sndr'].'">'.$name.'</a>';
        }
        $returned['exit_id'] = $dict->getString($res->getLevelString());
        
        $name = $character->getLname($res->getDestinationLocationID());
        if (!$name) {
            $name = Model_Dict::getInstance($this->source)->getString('unknown_location');
        }
        $returned['loc_id'] = HTML::anchor('user/location/nameform/'.$res->getDestinationLocationID(), $name);
        
        return $returned;
        
    }
    
    public function send() {}

}

?>
