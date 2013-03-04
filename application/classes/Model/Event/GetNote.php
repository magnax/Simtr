<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event_GetNote extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia GET_NOTE
     */

    /**
     * note title (saved as is)
     *
     * @var <type> string
     */
    protected $note_title;

    public function setNote($note_title) {
        $this->note_title = $note_title;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['note_title'] = $this->note_title;
        $arr['sndr'] = $this->sender;

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
        
        $returned['note_title'] = $event_data['note_title'];
        
        return $returned;
        
    }    
    
    public function send() {}

}

?>

