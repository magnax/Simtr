<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event {

    const EAT = 'Eat';
    const ENTER_LOCATION = 'EnterLocation';
    const GET_ITEM = 'GetItem';
    const GET_NOTE = 'GetNote';
    const GET_RAW = 'GetRaw';
    const GET_RAW_END = 'GetRawEnd';
    const GIVE_ITEM = 'GiveItem';
    const GIVE_RAW = 'GiveRaw';
    const GOD_TALK = 'GodTalk';
    const HIT_PERSON = 'HitPerson';
    const HUNGRY = 'Hungry';
    const KILL_PERSON = 'KillPerson';
    const POINT_EXIT = 'PointExit';
    const POINT_PERSON = 'PointPerson';
    const PUT_RAW = 'PutRaw';
    const PUT_ITEM = 'PutItem';
    const PUT_NOTE = 'PutNote';
    const SPAWN = 'Spawn';
    const TALK_ALL = 'TalkAll';
    const TALK_TO = 'TalkTo';
    const USE_RAW = 'UseRaw';
    
    /**
     * wspólne właściwości
     */
    public $id;
    public $date;
    public $type;
    
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

    public function values(array $event_data) {
        foreach ($event_data as $k => $v) {
            $this->$k = $v;
        }
        return $this;
    }

    public static function findById($id, $source) {
        
        $event_data = json_decode($source->get("events:$id"), true);
        if ($event_data) {
            $src = 'Model_Event_'.$event_data['type'];
            $event = new $src($event_data['type'], null, $source);
            return $event->values($event_data);
        }
        return null;
        
    }

    public function getSource() {
        return $this->source;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
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
            'id' => $this->id,
            'date' => $this->date,
            'type' => $this->type,
            'sndr' => $this->sender
        );
        
    }
    
    public function dispatchArgs(array $args, Model_Character $character, $lang) {
 
        $returned = array();

        //make arguments in proper order and set initial values
        foreach ($args as $key) {
            $returned[$key] = null;
        }
        
        //this should rather go to location related events
        if (in_array('loc_type', $args)) {
            $returned['loc_type'] = ORM::factory('LocationClass', $this->loc_type)->name;
        }
        
        if (in_array('sndr', $args)) {
            $returned['sndr'] = $character->getChNameLink($this->sndr);
        }
        
        if (in_array('rcpt', $args)) {
            $returned['rcpt'] = $character->getChNameLink($this->rcpt);
        }
        
        if (in_array('text', $args)) {
            $returned['text'] = $this->text;
        }
        
        return $returned;
        
    }

}

?>
