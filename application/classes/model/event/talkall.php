<?php

class Model_Event_TalkAll extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia TALK_ALL
     */
    protected $text;
    protected $sender;

    public function setText($t) {
        $this->text = $t;
    }

    public function setSender($ch) {
        $this->sender = $ch;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['text'] = $this->text;
        $arr['sndr'] = $this->sender;

        return $arr;

    }

    public function send() {}
    
}

?>
