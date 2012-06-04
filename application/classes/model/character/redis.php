<?php

class Model_Character_Redis extends Model_Character {

    public function save() {

        if (!$this->id) {
            $this->id = $this->source->incr("global:IDCharacter");
            $this->source->sadd("global:characters", $this->id);
        }
        
        $this->source->set("characters:{$this->id}", json_encode($this->toArray()));

    }

    public function fetchOne($id) {

        $char = new Model_Character_Redis($this->source, $this->chnames);
        $tmp_char = $this->source->get("characters:$id");
        if ($tmp_char) {
            $tmp_char = json_decode($tmp_char, true);
            //"hydration":
            foreach ($tmp_char as $key => $val) {
                $char->{$key} = $val;
            }

            return $char;
        }
        return null;
    }

    public function getInfo(array $characters) {

        $chars = array();
        foreach ($characters as $ch) {
            $new_char = new Model_Character_Redis($this->source);
            $chars[] = $new_char->fetchOne($ch);
        }

        return $chars;

    }

    public function getCollection(array $array_id) {
        
    }

    public function getEvents() {
        $size = $this->source->llen("characters:{$this->id}:events");
        if ($size) {
            $events = $this->source->lrange("characters:{$this->id}:events", 0, $size);
        } else {
            return array();
        }
        $return_events = array();

        //$event_dispatcher = Model_EventDispatcher::getInstance($this->source);
        //$event_dispatcher->setIdCharacter($this->id);

        foreach ($events as $id_event) {

            //$return_events[] = $event_dispatcher->formatEvent($event);
            //get and decode event
            $event = json_decode($this->source->get("events:$id_event"), true);

            //check if current character is sender, recipient or just viewer of the event
            if ($event['sndr'] == $this->id) {
                $person = 1;
            } elseif (isset($event['rcpt']) && $event['rcpt'] == $this->id) {
                $person = 2;
            } else {
                $person = 3;
            }

            //get display format and params
            $format = $this->source->get("global:event_tpl:{$event['type']}:$person");
            $args = $this->source->lrange("global:event_tpl:{$event['type']}:$person:params", 0, -1);
            //delegate further dispatching to proper event model
            $event_object = Model_Event::getInstance($event['type'], NULL, $this->source);
            $args = $event_object->dispatchArgs($event, $args, $this->id, $this->chnames);

            if (!$format) {
                $event['text'] = 'coś nie tak...('.$id_event.')';
            } else {
                $event['text'] = @vsprintf($format, $args);
                if ($person == 2) {
                    $event['text'] = '<b>'.$event['text'].'</b>';
                }
                if (!$event['text']) {
                    $event['text'] = 'ERROR: <b>'.$format.'</b> L.arg.:'.count($args).' Person:'.$person. ' Event: '.$id_event;
                }
            }

            $return_events[] = array(
                'date'=>$event['date'],
                'text'=>'('.$id_event.') '.$event['text']
            );
            
        }
               
        return $return_events;
    }

    public function calculateEqWeight() {

    }

    /**
     * pobiera surowce z inwentarza
     */
    public function getRaws() {

        $raws = json_decode($this->source->get("raws:{$this->id}"), true);
        $tmp = array();
        if ($raws) {
            foreach ($raws as $k => $v) {
                $tmp[$k] = array(
                    'id'=>$k,
                    'name'=>$this->source->get("resources:$k:names:d"),
                    'amount'=>$v
                );
            }
        }
        return $tmp;

    }

    public function putRaw($id, $amount) {

        $raws = json_decode($this->source->get("raws:{$this->id}"), true);
        
        if ($raws) {
            $raws[$id] -= $amount;
            if ($raws[$id] <= 0) {
                unset($raws[$id]);
            }
            $this->eq_weight -= $amount;
            $this->save();
            
            $this->source->set("raws:{$this->id}", json_encode($raws));
        }

    }

    public function addRaw($id, $amount) {

        $raws = json_decode($this->source->get("raws:{$this->id}"), true);
        
        if ($raws) {
            if (in_array($id, array_keys($raws))) {
                $raws[$id] += $amount;
            } else {
                $raws[$id] = $amount;
            }
        } else {
            $raws[$id] = $amount;
        }

        $this->eq_weight += $amount;
        $this->save();

        $this->source->set("raws:{$this->id}", json_encode($raws));

    }

    protected function saveRaws($raws) {

        $this->source->set("raws:{$this->id}", json_encode($raws));

    }

}

?>
