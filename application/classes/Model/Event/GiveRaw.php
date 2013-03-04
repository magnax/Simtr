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
     * postać odbierająca surowiec
     * 
     * @var <type> 
     */
    protected $recipient;

    public function setResource($res_id, $amount) {
        $this->res_id = $res_id;
        $this->amount = $amount;
    }

    public function setRecipient($ch) {
        $this->recipient = $ch;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['res_id'] = $this->res_id;
        $arr['amount'] = $this->amount;
        $arr['rcpt'] = $this->recipient;

        return $arr;

    }
    
    public function dispatchArgs(array $args, Model_Character $character, $lang) {
        
        $returned = parent::dispatchArgs($args, $character, $lang);
        
        $res = ORM::factory('Resource', $this->res_id)->d;
        
        if (in_array('amount', $args)) {
            $returned['amount'] = $this->amount;
        }
        
        $returned['res_id'] = $res;
        
        return $returned;
        
    }
    
    public function send() {}

}

?>