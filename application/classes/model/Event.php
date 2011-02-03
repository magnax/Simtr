<?php

class Model_Event {

    const TALK_ALL = 'TalkAll';
    const TALK_TO = 'TalkTo';
    const PUT_RAW = 'PutRaw';
    const GET_RAW = 'GetRaw';
    const GET_RAW_END = 'GetRawEnd';
    const SPAWN = 'Spawn';
    const GIVE_RAW = 'GiveRaw';

    /**
     * wspólne właściwości
     */
    protected $date;
    protected $type;
    protected $recipients = array();

    protected $source;

    public function  __construct($type, $date, $source) {
        $this->type = $type;
        $this->date = $date;
        $this->source = $source;
    }

    public static function getInstance($type, $date, $source) {
        //if ($source instanceof Predis_Client) {
            $src = 'Model_Event_'.$type;
            //$src = 'Model_Event_'.$type.'_Redis';
            return new $src($type, $date, $source);
        //}
    }

    public function getSource() {
        return $this->source;
    }

    public function addRecipients(array $recipients) {
        $this->recipients = $recipients;
    }

    public function getRecipients() {
        return $this->recipients;
    }

    public function toArray() {
        return array(
            'date'=>$this->date,
            'type'=>$this->type
        );
    }

}

?>
