<?php

class Model_Event_PutRaw extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia PUT_RAW
     */

    /**
     * identyfikator surowca
     *
     * @var <type> int
     */
    protected $res_id;

    /**
     * ilość surowca
     *
     * @var <type> int
     */
    protected $amount;
    protected $sender;

    public function  __construct($date, $source) {
        $this->type = self::PUT_RAW;
        $this->source = $source;
        $this->date = $date;
    }

    public function setResource($res_id, $amount) {
        $this->res_id = $res_id;
        $this->amount = $amount;
    }

    public function setSender($ch) {
        $this->sender = $ch;
    }

    public function send() {

    }

}

?>