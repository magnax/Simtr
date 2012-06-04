<?php

class Model_Event_GetRaw extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia GET_RAW
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

        return $arr;

    }

    public function dispatchArgs($event_data, $args, $character_id, $chname) {
        
        $dict = Model_Dict::getInstance($this->source);
        
        $res = Model_Resource::getInstance($this->source)
            ->findOneById($event_data['res_id'])
            ->getDictionaryName('d');
        
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $returned['sndr'] = html::anchor('user/char/nameform/'.$event_data['sndr'], 
                $chname->getName($character_id, $event_data['sndr']));
        }
        
        if (in_array('amount', $args)) {
            $returned['amount'] = $event_data['amount'];
        }
        
        $returned['res_id'] = $res;
        
        return $returned;
        
    }

    public function send() {}

}

?>