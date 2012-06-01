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

    public function dispatchArgs($event_data, $args, $character_id) {
    
        $dict = Model_Dict::getInstance($this->source);
        $chname = Model_ChNames::getInstance($this->source, $dict);
        
        $lname = Model_LNames::getInstance($this->source, $dict);
        $lname->setCharacter($character_id);
        
        $res = Model_Road::getInstance($this->source)->findOneByID($event_data['exit_id']);
            
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $returned['sndr'] = html::anchor('u/char/nameform/'.$event_data['sndr'], 
                $chname->getName($character_id, $event_data['sndr']));
        }
        $returned['exit_id'] = $dict->getString($res->getLevelString());
        
        $returned['loc_id'] = html::anchor('u/location/nameform/'.$res->getDestinationLocationID(), 
            $lname->getName($res->getDestinationLocationID()));
        
        return $returned;
        
    }
    
    public function send() {}

}

?>
