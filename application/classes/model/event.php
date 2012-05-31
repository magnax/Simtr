<?php

class Model_Event {

    const TALK_ALL = 'TalkAll';
    const TALK_TO = 'TalkTo';
    const PUT_RAW = 'PutRaw';
    const GET_RAW = 'GetRaw';
    const GET_RAW_END = 'GetRawEnd';
    const SPAWN = 'Spawn';
    const GIVE_RAW = 'GiveRaw';
    const POINT_EXIT = 'PointExit';

    /**
     * wspólne właściwości
     */
    protected $date;
    protected $type;
    
    //sender may remains null if event is send by the system
    protected $sender = null;
    
    protected $recipients = array();

    protected $source;

    public function  __construct($type, $date, $source) {
        $this->type = $type;
        $this->date = $date;
        $this->source = $source;
    }

    public static function getInstance($type, $date, $source) {

            $src = 'Model_Event_'.$type;
            return new $src($type, $date, $source);

    }

    public function getSource() {
        return $this->source;
    }
    
    public function setSender($ch) {
        $this->sender = $ch;
    }
    
    public function addRecipients(array $recipients) {
        $this->recipients = $recipients;
    }

    public function getRecipients() {
        return $this->recipients;
    }

    public function toArray() {
        
        return array(
            'date' => $this->date,
            'type' => $this->type,
            'sndr' => $this->sender
        );
        
    }

}

?>
