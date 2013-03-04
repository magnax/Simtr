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

    public function dispatchArgs($event_data, $args, $character_id, $lang) {
        
        $res = ORM::factory('resource', $event_data['res_id'])->d;
        
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['sndr'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['sndr'], $lang);
            }
            $returned['sndr'] = '<a href="chname?id='.
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