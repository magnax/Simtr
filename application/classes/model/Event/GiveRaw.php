<?php

class Model_Event_GiveRaw extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia GIVE_RAW
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

    /**
     * postać przekazująca surowiec
     *
     * @var <type>
     */
    protected $sender;
    
    /**
     * postać odbierająca surowiec
     * 
     * @var <type> 
     */
    protected $recipient;

    public function setResource($res_id, $amount) {
        $this->res_id = $res_id;
        $this->amount = $amount;
    }

    public function setSender($ch) {
        $this->sender = $ch;
    }

    public function setRecipient($ch) {
        $this->recipient = $ch;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['res_id'] = $this->res_id;
        $arr['amount'] = $this->amount;
        $arr['sndr'] = $this->sender;
        $arr['rcpt'] = $this->recipient;

        return $arr;

    }

    public function send() {}

}

?>