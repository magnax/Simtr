<?php defined('SYSPATH') or die('No direct script access.');

class Model_EventDispatcher_Redis extends Model_EventDispatcher {

    public function formatEvent($id_event, $id_character) {

        //get and decode event
        $event = json_decode($this->source->get("events:$id_event"), true);
        
        //print_r($event); exit;
        
        //print_r($event);echo $id_event.'<br><br>';

        //check if current character is sender, recipient or just viewer of the event
        if ($event['sndr'] == $id_character) {
            $person = 1;
        } elseif (isset($event['rcpt']) && $event['rcpt'] == $id_character) {
            $person = 2;
        } else {
            $person = 3;
        }
        
        //get display format and params
        $format = $this->source->get("global:event_tpl:{$event['type']}:$person");
        $args = $this->source->lrange("global:event_tpl:{$event['type']}:$person:params", 0, -1);
        //delegate further dispatching to proper event model
        $event_object = Model_Event::getInstance($event['type'], NULL, $this->source);
        $args = $event_object->dispatchArgs($event, $args, $id_character, $this->lang);

        if (!$format) {
            $event['text'] = 'coś nie tak...('.$id_event.', person: '.$person.')';
        } else {
            $event['text'] = @vsprintf($format, $args);
            if ($person == 2) {
                $event['text'] = '<b>'.$event['text'].'</b>';
            }
            if (!$event['text']) {
                $event['text'] = 'ERROR: <b>'.$format.'</b> L.arg.:'.count($args).' Person:'.$person. ' Event: '.$id_event;
            }
        }
        
        $event['text'][0] = strtoupper($event['text'][0]);

        return array(
            'id'  => $id_event,
            'date'=> Model_GameTime::formatDateTime($event['date']),
            'text'=>'('.$id_event.') '.$event['text']
        );

    }

}

?>
