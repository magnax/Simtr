<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event_TalkAll extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia TALK_ALL
     */
    protected $text;

    public function setText($t) {
        $this->text = $t;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['text'] = $this->text;

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
        
        $returned['text'] = $event_data['text'];
        
        return $returned;
        
    }
    
    public function send() {}
    
}

?>
