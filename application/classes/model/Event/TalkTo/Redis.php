<?php

class Model_Event_TalkTo_Redis extends Model_Event_TalkTo {
    
    /**
     * wysyła (dopisuje) zdarzenie do wszystkich odbiorców
     */
    public function send() {

        $event = array(
            'date'=>$this->date,
            'type'=>$this->type,
            'sndr'=>$this->sender,
            'rcpt'=>$this->recipients[0]->getID(),
            'text'=>$this->text
        );
        $serialised_event = json_encode($event);

        //zapisać samo zdarzenie:
        $event_id = $this->source->incr('global:IDEvent');
        $this->source->set("events:$event_id", $serialised_event);

        //odbiorcy dopisać do kolejki zdarzeń:
        $this->source->lpush("characters:{$this->recipients[0]->getID()}:events", $event_id);
        //i sobie dopisać:
        $this->source->lpush("characters:{$this->sender}:events", $event_id);

    }

}

?>
