<?php

class Model_Event_GetRawEnd_Redis extends Model_Event_GetRawEnd {

    /**
     * wysyła (dopisuje) zdarzenie do wszystkich odbiorców
     */
    public function send() {

        $event = array(
            'date'=>$this->date,
            'type'=>$this->type,
            'sndr'=>$this->sender,
            'res_id'=>$this->res_id,
            'amount'=>$this->amount
        );
        $serialised_event = json_encode($event);

        //zapisać samo zdarzenie:
        $event_id = $this->source->incr('global:IDEvent');
        $this->source->set("events:$event_id", $serialised_event);

        //każdemu odbiorcy dopisać do kolejki zdarzeń:
        foreach ($this->recipients as $r) {
            $this->source->lpush("characters:$r:events", $event_id);
        }

    }

}

?>