<?php

class Model_Event_TalkAll extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia TALK_ALL
     */
    protected $text;

    public function setText($t) {
        $this->text = $t;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['text'] = $this->text;

        return $arr;

    }

    public function dispatchArgs($event_data, $args, $character_id, $chname) {
        
        $dict = Model_Dict::getInstance($this->source);
        
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $returned['sndr'] = html::anchor('user/char/nameform/'.$event_data['sndr'], 
                $chname->getName($character_id, $event_data['sndr']));
        }
        
        $returned['text'] = $event_data['text'];
        
        return $returned;
        
    }
    
    public function send() {}
    
}

?>
