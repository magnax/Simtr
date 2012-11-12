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

    public function dispatchArgs($event_data, $args, $character_id, $lang) {
        
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['sndr'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['sndr'], $lang);
            }
            $returned['sndr'] = '<a href="chname?id='.
                $event_data['sndr'].'">'.$name.'</a>';
        }
        
        if (in_array('desc', $args)) {
            $returned['desc'] = $event_data['desc'];
        }
        
        return $returned;
        
    }
    
    public function send() {}

}

?>


