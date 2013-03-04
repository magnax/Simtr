<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event_PointPerson extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia POINT_PERSON
     */
    protected $recipient;

    public function setRecipient($ch) {
        $this->recipient = $ch;
    }
    
    public function toArray() {

        $arr = parent::toArray();

        $arr['sndr'] = $this->sender;
        $arr['rcpt'] = $this->recipient;

        return $arr;

    }
    
    public function send() {}

}

?>

