<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event_TalkTo extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia TALK_TO
     */
    protected $text;
    protected $recipient;

    public function setText($t) {
        $this->text = $t;
    }

    public function setRecipient($ch) {
        $this->recipient = $ch;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['text'] = $this->text;
        $arr['sndr'] = $this->sender;
        $arr['rcpt'] = $this->recipient;

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
        
        if (in_array('rcpt', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['rcpt'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['rcpt'], $lang);
            }
            $returned['rcpt'] = '<a href="chname?id='.
                $event_data['rcpt'].'">'.$name.'</a>';
        }
        
        $returned['text'] = $event_data['text'];
        
        return $returned;
        
    }
    
    public function send() {}

}

?>
