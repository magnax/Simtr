<?php defined('SYSPATH') or die('No direct script access.');

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

    public function setResource($res_id, $amount) {
        $this->res_id = $res_id;
        $this->amount = $amount;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['res_id'] = $this->res_id;
        $arr['amount'] = $this->amount;
        $arr['sndr'] = $this->sender;

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