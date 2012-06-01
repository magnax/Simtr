<?php

class Model_Event_TalkTo extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia TALK_TO
     */
    protected $text;
    protected $recipient;

    public function setText($t) {
        $this->text = $t;
    }

    public function setRecipient($ch) {
        $this->recipient = $ch;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['text'] = $this->text;
        $arr['sndr'] = $this->sender;
        $arr['rcpt'] = $this->recipient;

        return $arr;

    }

    public function dispatchArgs($event_data, $args, $character_id) {
        
        $dict = Model_Dict::getInstance($this->source);
        $chname = Model_ChNames::getInstance($this->source, $dict);
        
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $returned['sndr'] = html::anchor('u/char/nameform/'.$event_data['sndr'], 
                $chname->getName($character_id, $event_data['sndr']));
        }
        
        if (in_array('rcpt', $args)) {
            $returned['rcpt'] = html::anchor('u/char/nameform/'.$event_data['rcpt'], 
                $chname->getName($character_id, $event_data['rcpt']));
        }
        
        $returned['text'] = $event_data['text'];
        
        return $returned;
        
    }
    
    public function send() {}

}

?>
