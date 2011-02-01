<?php

class Model_Character_Redis extends Model_Character {
    
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

    /**
     * zwraca zapamiętane imię podanej postaci
     */
    public function getChName($id) {
        $name = $this->source->get("characters:{$this->id}:chnames:$id");

        return $name ? $name : '20-latek';
    }

    public function getEvents() {
        $size = $this->source->llen("characters:{$this->id}:events");
        if ($size) {
            $events = $this->source->lrange("characters:{$this->id}:events", 0, $size);
        } else {
            return array();
        }
        $return_events = array();
        foreach ($events as $event) {

            $e = json_decode($this->source->get("events:$event"), true);
            
            if ($e['sndr'] == $this->id) {
                $person = 1;
            } elseif (isset($e['rcpt']) && $e['rcpt'] == $this->id) {
                $person = 2;
            } else {
                $person = 3;
            }
            
            $format = $this->source->get("global:event_tpl:{$e['type']}:$person");
            $args = $this->source->lrange("global:event_tpl:{$e['type']}:$person:params", 0, -1);
            
            foreach($args as &$a) {
                if ($a == 'sndr' || $a == 'rcpt') {                   
                    $a = html::anchor('u/char/nameform/'.$e[$a], $this->getChName($e[$a]));
                } elseif ($a == 'res_id') {
                    $res = Model_Resource::getInstance($this->source)
                            ->findOneById($e[$a])
                            ->getName('d');
                    $a = $res;
                } else {
                    $a = $e[$a];
                }
            }
            if (!$format || !$args) {
                $e['text'] = 'coś nie tak...';
            } else {
                $e['text'] = @vsprintf($format, $args);
                if (!$e['text']) {
                    $e['text'] = 'ERROR: '.$format.' L.arg.:'.count($args).' Person:'.$person;
                }
            }

            $return_events[] = $e;
            
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

        $this->source->del("characters:{$this->id}:equipment:raws");

        foreach ($raws as $r) {
            $tmp = array($r['id']=>$r['amount']);
            $this->source->sadd("characters:{$this->id}:equipment:raws", json_encode($tmp));
        }

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


        $this->source->del("characters:{$this->id}:equipment:raws");

        foreach ($raws as $r) {
            $tmp = array($r['id']=>$r['amount']);
            $this->source->sadd("characters:{$this->id}:equipment:raws", json_encode($tmp));
        }
    }

}

?>
