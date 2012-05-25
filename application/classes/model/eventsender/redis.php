<?php

class Model_EventSender_Redis extends Model_EventSender {

    /**
     * wysyła (dopisuje) zdarzenie do wszystkich odbiorców
     */
    public function send() {

        $event = $this->_event->toArray();
        $source = $this->_event->getSource();

        $serialised_event = json_encode($event);

        //zapisać samo zdarzenie:
        $event_id = $source->incr('global:IDEvent');
        $source->set("events:$event_id", $serialised_event);

        //każdemu odbiorcy dopisać do kolejki zdarzeń:
        foreach ($this->_event->getRecipients() as $r) {
            $source->lpush("characters:$r:events", $event_id);
        }

    }

}

?>