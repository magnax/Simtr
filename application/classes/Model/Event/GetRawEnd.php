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

    public function dispatchArgs(array $args, Model_Character $character, $lang) {
        
        $returned = parent::dispatchArgs($args, $character, $lang);
        
        /**
         * @todo: res_id / resource_id inconsistency
         */
        $project_data = array(
            'type_id'=>$this->type,
            'resource_id'=>$this->res_id
        );
        
        $returned['name'] = Model_Project::getInstance('GetRaw')
            ->name($project_data, $character->id);
         
        if (in_array('amount', $args)) {
            $returned['amount'] = $this->amount . ' gram';
        }

        return $returned;
        
    }
    
    public function send() {}

}

?>