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
    
}

?>
