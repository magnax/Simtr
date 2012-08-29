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

    public function dispatchArgs($event_data, $args, $character) {
        
        $res = Model_Resource::getInstance($this->source)
            ->findOneById($event_data['res_id'])
            ->getDictionaryName('d');
        
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $name = $character->getChname($event_data['sndr']);
            if (!$name) {
                $name = $character->getUnknownName($event_data['sndr']);
                $name = Model_Dict::getInstance($this->source)->getString($name);
            }
            $returned['sndr'] = '<a href="/user/char/nameform/'.
                $event_data['sndr'].'">'.$name.'</a>';
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