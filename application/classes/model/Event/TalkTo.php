<?php

class Model_Event_TalkTo extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia TALK_TO
     */
    protected $text;
    protected $sender;

    public function  __construct($date, $source) {
        $this->type = self::TALK_TO;
        $this->source = $source;
        $this->date = $date;
    }

    public function setText($t) {
        $this->text = $t;
    }

    public function setSender($ch) {
        $this->sender = $ch;
    }

    public function send() {

    }

}

?>
