<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event {

    const TALK_ALL = 'TalkAll';
    const TALK_TO = 'TalkTo';
    const PUT_RAW = 'PutRaw';
    const PUT_ITEM = 'PutItem';
    const GET_ITEM = 'GetItem';
    const GET_RAW = 'GetRaw';
    const GET_RAW_END = 'GetRawEnd';
    const SPAWN = 'Spawn';
    const GIVE_RAW = 'GiveRaw';
    const POINT_EXIT = 'PointExit';
    const POINT_PERSON = 'PointPerson';
    const HIT_PERSON = 'HitPerson';
    const KILL_PERSON = 'KillPerson';
    const ENTER_LOCATION = 'EnterLocation';
    const HUNGRY = 'Hungry';
    const EAT = 'Eat';

    /**
     * wspólne właściwości
     */
    protected $id;
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
    
    public function dispatchArgs($event_data, $args, $character_id, $lang) {
        
        $returned = array();
        
        if (in_array('loc_type', $args)) {
            $returned['loc_type'] = ORM::factory('locationclass', $event_data['loc_type'])->name;
        }
        
        if (in_array('sndr', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['sndr'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['sndr'], $lang);
            }
            $returned['sndr'] = '<a href="/chname?id='.
                $event_data['sndr'].'">'.$name.'</a>';
        }
        
        if (in_array('rcpt', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['rcpt'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['rcpt'], $lang);
            }
            $returned['rcpt'] = '<a href="/chname?id='.
                $event_data['rcpt'].'">'.$name.'</a>';
        }
        
        if (in_array('text', $args)) {
            $returned['text'] = $event_data['text'];
        }
        
        return $returned;
        
    }

}

?>
