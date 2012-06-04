<?php

class Model_Event_GetRawEnd extends Model_Event {

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

    public function dispatchArgs($event_data, $args, $character_id, $chname) {
        //return;
        $dict = Model_Dict::getInstance($this->source);
        
        $res = Model_Resource::getInstance($this->source)
            ->findOneById($event_data['res_id']);
        
        $res_name = $res->getDictionaryName('d');
        $res_action = $res->getType();
        
        $returned = array();
        
        $returned['name'] = $dict->getString($res_action);
        $returned['res_id'] = $res_name;
        
        if (in_array('amount', $args)) {
            $returned['amount'] = $event_data['amount'];
        }
        
        if (in_array('sndr', $args)) {
            $returned['sndr'] = html::anchor('user/char/nameform/'.$event_data['sndr'], 
                $chname->getName($character_id, $event_data['sndr']));
        }

        return $returned;
        
    }
    
    public function send() {}

}

?>