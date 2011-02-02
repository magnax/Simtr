<?php

class Model_Character_Redis extends Model_Character {

    public function createNew($time, $id_location) {

        if (!$this->id) {
            $this->id = $this->source->incr("global:IDCharacter");
        }
        $this->spawn_date = $time;
        $this->spawn_location_id = $id_location;
        $this->location_id = $id_location;
        $this->eq_weight = 0;
        $this->place_type = 'loc';
        $this->place_id = $id_location;

    }

    public function save() {

        $tmp_char = $this->toArray();
        unset($tmp_char['age']);
        
        $this->source->set("characters:{$this->id}", json_encode($tmp_char));

    }

    public function fetchOne($id) {

        $char = new Model_Character_Redis($this->source);
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

        $event_dispatcher = Model_EventDispatcher::getInstance($this->source);
        $event_dispatcher->setIdCharacter($this->id);

        foreach ($events as $event) {

            $return_events[] = $event_dispatcher->formatEvent($event);
            
        }
               
        return $return_events;
    }

    public function calculateEqWeight() {

    }

    /**
     * pobiera surowce z inwentarza
     */
    public function getRaws() {

        $raws = $this->source->smembers("characters:{$this->id}:equipment:raws");
        $tmp = array();
        foreach ($raws as $r) {
            $raw = json_decode($r, true);
            $ak = array_keys($raw);
            $tmp[$ak[0]] = array(
              'id'=>$ak[0],
                'name'=>$this->source->get("resources:{$ak[0]}:names:d"),
                'amount'=>$raw[$ak[0]]
            );
        }
        return $tmp;

    }

    public function putRaw($id, $amount) {

        $raws = $this->getRaws();
        $raws[$id]['amount'] -= $amount;
        if ($raws[$id]['amount'] <= 0) {
            unset($raws[$id]);
        }
        $this->eq_weight -= $amount;
        $this->save();

        $this->saveRaws($raws);

    }

    public function addRaw($id, $amount) {

        $raws = $this->getRaws();
        if (isset($raws[$id])) {
            $raws[$id]['amount'] += $amount;
        } else {
            $raws[$id] = array(
              'id'=>$id,
                'amount'=>$amount,
                'name'=>''
            );
        }

        $this->eq_weight += $amount;
        $this->save();

        $this->saveRaws($raws);

    }

    protected function saveRaws($raws) {

        $this->source->del("characters:{$this->id}:equipment:raws");

        foreach ($raws as $r) {
            $tmp = array($r['id']=>$r['amount']);
            $this->source->sadd("characters:{$this->id}:equipment:raws", json_encode($tmp));
        }

    }

}

?>
