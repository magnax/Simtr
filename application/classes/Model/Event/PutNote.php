<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event_PutNote extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia PUT_NOTE
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

    public function dispatchArgs(array $args, Model_Character $character, $lang) {
        
        $returned = parent::dispatchArgs($args, $character, $lang);
        
        $returned['note_title'] = $this->note_title;
        
        return $returned;
        
    }    
    
    public function send() {}

}

?>
