<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event_Hungry extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia HUNGRY
     */
    protected $desc;
    
    public function setDesc($desc) {
        $this->desc = $desc;
    }

    public function toArray() {

    $arr = parent::toArray();

    $arr['sndr'] = $this->sender;
    $arr['desc'] = $this->desc;

    return $arr;

    }

    public function dispatchArgs(array $args, Model_Character $character, $lang) {
        
        $returned = parent::dispatchArgs($args, $character, $lang);
        
        if (in_array('desc', $args)) {
            $returned['desc'] = $this->desc;
        }
        
        return $returned;
        
    }
    
    public function send() {}

}

?>


