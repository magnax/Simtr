<?php defined('SYSPATH') or die('No direct script access.');

class Model_EventDispatcher_Redis extends Model_EventDispatcher {

    public function formatEvent($id_event, Model_Character $character) {

        $event = Model_Event::findById($id_event, $this->source);
        
        //check if current character is sender, recipient or just viewer of the event
        $person = 3;
        if ($event->sndr == $character->id) {
            $person = 1;
        } elseif (isset($event->rcpt) && $event->rcpt == $character->id) {
            $person = 2;
        }
        
        //get display format and params
        $format = $this->source->get("global:event_tpl:{$event->type}:$person");
        $event_args = $this->source->lrange("global:event_tpl:{$event->type}:$person:params", 0, -1);
        //delegate further dispatching to proper event model
        $args = $event->dispatchArgs($event_args, $character, $this->lang);

        if (!$format) {
            $text = 'coś nie tak...('.$id_event.', person: '.$person.')';
        } else {
            $text = @vsprintf($format, $args);
            if ($person == 2) {
                $text = '<b>'.$text.'</b>';
            }
            if (!$text) {
                $text = 'ERROR: <b>'.$format.'</b> L.arg.:'.count($args).' Person:'.$person. ' Event: '.$id_event;
                print_r($event_args);
            }
        }

        return array(
            'id'  => $id_event,
            'date'=> Model_GameTime::formatDateTime($event->date),
            'text'=>'('.$id_event.') '.$text
        );

    }

}

?>
