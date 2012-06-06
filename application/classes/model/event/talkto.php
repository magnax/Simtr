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

    public function dispatchArgs($event_data, $args, $character) {
        
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $name = $character->getChname($event_data['sndr']);
            if (!$name) {
                $name = $character->getUnknownName($event_data['sndr']);
                $name = Model_Dict::getInstance($this->source)->getString($name);
            }
            $returned['sndr'] = '<a href="/user/char/nameform/'.
                $event_data['sndr'].'">'.$name.'</a>';
        }
        
        if (in_array('rcpt', $args)) {
            $name = $character->getChname($event_data['rcpt']);
            if (!$name) {
                $name = $character->getUnknownName($event_data['rcpt']);
                $name = Model_Dict::getInstance($this->source)->getString($name);
            }
            $returned['rcpt'] = '<a href="/user/char/nameform/'.
                $event_data['rcpt'].'">'.$name.'</a>';
        }
        
        $returned['text'] = $event_data['text'];
        
        return $returned;
        
    }
    
    public function send() {}

}

?>
