<?php defined('SYSPATH') or die('No direct script access.');

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

    public function dispatchArgs($event_data, $args, $character_id, $lang) {
        
        $returned = array();
        
        /**
         * @todo: res_id / resource_id inconsistency
         */
        $project_data = array(
            'type_id'=>$event_data['type'],
            'resource_id'=>$event_data['res_id']
        );
        
        $returned['name'] = Model_Project::getInstance('GetRaw')
                ->name($project_data, $character_id);
        
        if (in_array('amount', $args)) {
            $returned['amount'] = $event_data['amount'];
        }
        
        if (in_array('sndr', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['sndr'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['sndr'], $lang);
            }
            $returned['sndr'] = '<a href="chname?id='.
                $event_data['sndr'].'">'.$name.'</a>';
        }

        return $returned;
        
    }
    
    public function send() {}

}

?>