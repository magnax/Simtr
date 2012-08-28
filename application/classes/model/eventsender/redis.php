<?php defined('SYSPATH') or die('No direct script access.');

class Model_EventSender_Redis extends Model_EventSender {

    /**
     * wysyła (dopisuje) zdarzenie do wszystkich odbiorców
     */
    public function send() {

        $source = $this->_event->getSource();
        //new event create
        $event_id = $source->incr('global:IDEvent');

        //set event id to event object
        $this->_event->setId($event_id);
        
        //make serialised array from event object
        $event = $this->_event->toArray();
        $serialised_event = json_encode($event);
        
        $source->set("events:$event_id", $serialised_event);

        //każdemu odbiorcy dopisać do kolejki zdarzeń:
        foreach ($this->_event->getRecipients() as $r) {
            $source->lpush("characters:$r:events", $event_id);
        }

    }

}

?>