<?php

abstract class Model_Event {

    const TALK_ALL = 'TalkAll';
    const TALK_TO = 'TalkTo';
    const PUT_RAW = 'PutRaw';
    const GET_RAW = 'GetRaw';
    const GET_RAW_END = 'GetRawEnd';

    /**
     * wspólne właściwości
     */
    protected $date;
    protected $type;
    protected $recipients = array();

    protected $source;

    public function  __construct($source) {
        $this->source = $source;
    }

    public static function getInstance($typ, $date, $source) {
        if ($source instanceof Predis_Client) {
            $src = 'Model_Event_'.$typ.'_Redis';
            return new $src($date, $source);
        }
    }

    public function addRecipients(array $recipients) {
        $this->recipients = $recipients;
    }

    abstract public function send();
}

?>
