<?php

class Model_Event_TalkTo extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia TALK_TO
     */
    protected $text;
    protected $sender;
    protected $recipient;

    public function setText($t) {
        $this->text = $t;
    }

    public function setSender($ch) {
        $this->sender = $ch;
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

    public function send() {

    }

}

?>
