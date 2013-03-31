<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event extends OHM {

    protected $_columns = array('type', 'date');
    
    protected $_has_many = array(
        'params' => array(
            'model' => 'Param'
        )
    );

    const ARRIVE_INFO = 'ArriveInfo';
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

    public function setSender($ch) {
        $this->sender = $ch;
    }
    
    public function notify(array $recipients) {
        
        $elephant = new ElephantIOClient(Kohana::$config->load('general.server_ip'));
        try {
            $elephant->init();
        } catch (Exception $e) {
            $elephant_error = $e->getMessage();
        }
        
        //add event ID to each recipient events list
        foreach ($recipients as $character_id) {
            
            $notified_char = new Model_Character($character_id);
            
            $this->_redis->lpush("characters:$character_id:events", $this->id);
            
            Model_EventNotifier::notify($elephant, $notified_char, $this);
            
        }
           
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
    
    protected function get_format($person) {
        return RedisDB::instance()->get("global:event_tpl:{$this->type}:$person");
    }

    protected function get_required_params($person) {
        return RedisDB::instance()->lrange("global:event_tpl:{$this->type}:$person:params", 0, -1);
    }

    public function dispatch($character) {
            
        foreach ($this->params as $param) {
            $flattened_params[$param->name] = $param->value;
        }
        
        $person = 3;
        if (isset($flattened_params['sndr']) && ($flattened_params['sndr']== $character->id)) {
            $person = 1;
        } elseif (isset($flattened_params['rcpt']) && ($flattened_params['rcpt'] == $character->id)) {
            $person = 2;
        }

        $format = $this->get_format($person);
        if (!$format) {
            $text = 'coś nie tak...('.$this->id.', person: '.$person.')';
        } else {
            $required_params = $this->get_required_params($person);
            $dispatched_params = Model_Param::dispatch($flattened_params, $required_params, $character);
            $text = @vsprintf($format, $dispatched_params);
            if ($person == 2) {
                $text = '<b>'.$text.'</b>';
            }
            if (!$text) {
                $text = 'ERROR: <b>'.$format.'</b> L.arg.:'.count($required_params).' Person:'.$person. ' Event: '.$id_event;
            }
        }
        return $text;
    }
     
    public function format_output(Model_Character $character, $id) {
        
        if (!$this->loaded()) {
            return array(
                'id'  => $id,
                'date'=> 0,
                'text'=> $id.': malformed event',
            );
        } else {
            return array(
                'id'  => $this->id,
                'date'=> Model_GameTime::formatDateTime($this->date),
                'text'=> '('.$this->id.') ' . $this->dispatch($character)
            );
        }
        
    }
}

?>
