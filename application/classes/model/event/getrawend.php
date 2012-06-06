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

    public function dispatchArgs($event_data, $args, $character) {

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
            $name = $character->getChname($event_data['sndr']);
            if (!$name) {
                $name = $character->getUnknownName($event_data['sndr']);
                $name = Model_Dict::getInstance($this->source)->getString($name);
            }
            $returned['sndr'] = '<a href="/user/char/nameform/'.
                $event_data['sndr'].'">'.$name.'</a>';
        }

        return $returned;
        
    }
    
    public function send() {}

}

?>