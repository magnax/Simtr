<?php

class Model_EventDispatcher_Redis extends Model_EventDispatcher {

    public function formatEvent($id_event) {

        $event = json_decode($this->source->get("events:$id_event"), true);

        if ($event['sndr'] == $this->id_character) {
            $person = 1;
        } elseif (isset($event['rcpt']) && $event['rcpt'] == $this->id_character) {
            $person = 2;
        } else {
            $person = 3;
        }

        $format = $this->source->get("global:event_tpl:{$event['type']}:$person");
        $args = $this->source->lrange("global:event_tpl:{$event['type']}:$person:params", 0, -1);

        foreach($args as &$a) {
            if ($a == 'sndr' || $a == 'rcpt') {
                $chname = Model_ChNames::getInstance($this->source, Model_Dict::getInstance($this->source));
                $a = html::anchor('u/char/nameform/'.$event[$a], $chname->getName($this->id_character, $event[$a]));
            } elseif ($a == 'res_id') {
                $res = Model_Resource::getInstance($this->source)
                    ->findOneById($event[$a])
                    ->getName('d');
                $a = $res;
            } elseif ($a == 'exit_id') {
                $res = Model_Road::getInstance($this->source)
                    ->findOneByID($event[$a]);
                $a = Model_Dict::getInstance($this->source)->getString($res->getLevelString()).' ('.$event[$a].')';
            } else {
                $a = $event[$a];
            }
        }
        if (!$format) {
            $event['text'] = 'coś nie tak...('.$id_event.')';
        } else {
            $event['text'] = @vsprintf($format, $args);
            if ($person == 2) {
                $event['text'] = '<b>'.$event['text'].'</b>';
            }
            if (!$event['text']) {
                $event['text'] = 'ERROR: '.$format.' L.arg.:'.count($args).' Person:'.$person;
            }
        }

        return array(
            'date'=>$event['date'],
            'text'=>$event['text']
        );

    }

}

?>
