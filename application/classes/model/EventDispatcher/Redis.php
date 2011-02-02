<?php

class Model_EventDispatcher_Redis extends Model_EventDispatcher {

    public function formatEvent($id_event) {

        $e = json_decode($this->source->get("events:$id_event"), true);

        if ($e['sndr'] == $this->id_character) {
            $person = 1;
        } elseif (isset($e['rcpt']) && $e['rcpt'] == $this->id_character) {
            $person = 2;
        } else {
            $person = 3;
        }

        $format = $this->source->get("global:event_tpl:{$e['type']}:$person");
        $args = $this->source->lrange("global:event_tpl:{$e['type']}:$person:params", 0, -1);

        foreach($args as &$a) {
            if ($a == 'sndr' || $a == 'rcpt') {
                $chname = Model_ChNames::getInstance($this->source, Model_Dict::getInstance($this->source));
                $a = html::anchor('u/char/nameform/'.$e[$a], $chname->getName($this->id_character, $e[$a]));
            } elseif ($a == 'res_id') {
                $res = Model_Resource::getInstance($this->source)
                    ->findOneById($e[$a])
                    ->getName('d');
                $a = $res;
            } else {
                $a = $e[$a];
            }
        }
        if (!$format) {
            $e['text'] = 'coś nie tak...('.$id_event.')';
        } else {
            $e['text'] = @vsprintf($format, $args);
            if ($person == 2) {
                $e['text'] = '<b>'.$e['text'].'</b>';
            }
            if (!$e['text']) {
                $e['text'] = 'ERROR: '.$format.' L.arg.:'.count($args).' Person:'.$person;
            }
        }

        return array(
            'date'=>$e['date'],
            'text'=>$e['text']
        );

    }

}

?>
