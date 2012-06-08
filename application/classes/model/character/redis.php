<?php defined('SYSPATH') or die('No direct script access.');

class Model_Character_Redis extends Model_Character {

    public function save() {

        if (!$this->id) {
            $this->id = $this->source->incr("global:IDCharacter");
            $this->source->sadd("global:characters", $this->id);
        }
        
        $this->source->set("characters:{$this->id}", json_encode($this->toArray()));

    }

    public function fetchOne($id, $as_array = false) {

        $char = $this->source->get("characters:$id");
        
        if ($char) {
            
            $tmp_char = json_decode($char, true);
            if ($as_array) {
                return $tmp_char;
            }
            //"hydration":
            foreach ($tmp_char as $key => $val) {
                $this->{$key} = $val;
            }

            $this->getChnames();
            $this->getLnames();
            
            return $this;
            
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

    public function getEvents($page = 1) {
        
        //ile na stronę 
        $pagesize = 20;
        
        $size = $this->source->llen("characters:{$this->id}:events");
        if ($size) {
            $from = ($page - 1) * $pagesize;
            $events = $this->source->lrange("characters:{$this->id}:events", $from, $from + $pagesize - 1);
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
            $args = $event_object->dispatchArgs($event, $args, $this);

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
        
        //"pagination" ;) just info 
        $return_events[] = array(
            'date' => '',
            'prev' => ($page > 1) ? $page - 1 : '',
            'current' => $page,
            'next' => ($from + $pagesize < $size) ? $page + 1 : '',
        );
        
        return $return_events;
    }

    public function calculateEqWeight() {
        
        $weight = 0;
        
        //raw resources:
        $raws = json_decode($this->source->get("raws:{$this->id}"), true);

        foreach ($raws as $k => $raw) {
            $weight += $raw;
        }

        //items
        
        //keys
        
        return $weight;
        
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
            
            $this->source->set("raws:{$this->id}", json_encode($raws));
            
            $this->eq_weight = $this->calculateEqWeight();
            $this->save();
            
        }

    }

    public function addItem($item_id) {
        $this->source->sadd("char_items:{$this->id}", $item_id);
    }
    
    public function putItem($item_id) {
        $this->source->srem("char_items:{$this->id}", $item_id);
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
        
        $this->source->set("raws:{$this->id}", json_encode($raws));
        
        $this->eq_weight = $this->calculateEqWeight();
        $this->save();

    }

    protected function saveRaws($raws) {

        $this->source->set("raws:{$this->id}", json_encode($raws));

    }

    public function getItems() {
        
        $returned_items = array();
        $dict = Model_Dict::getInstance($this->source);
        $lang = $dict->getLang();
        
        $items_ids = $this->source->smembers("char_items:{$this->id}");
        foreach ($items_ids as $item_id) {
            $item = json_decode($this->source->get("global:items:$item_id"), true);
            $itemtype = json_decode($this->source->get("itemtype:{$item['type']}"), true);
            $itemkind = $this->source->get("kind:$lang:{$itemtype['name']}");
            if (!$itemkind) {
                $itemkind = 'm';
            }
            $state = Model_ItemType::getInstance($this->source)
                ->getState($item['points'] / $itemtype['points']).":$itemkind";
            
            $returned_items[] = array(
                'id' => $item_id,
                'name' => Model_Dict::getInstance($this->source)->getString($state) .
                ' ' .
                Model_Dict::getInstance($this->source)->getString($itemtype['name']),
            );
        }
        return $returned_items;
    }


    public function getChnames() {
        $chnames = $this->source->keys("chnames:{$this->id}:*");
        $returned = array();
        foreach($chnames as $ch) {
            $ch_key = explode(':', $ch);
            $returned[$ch_key[2]] = $this->source->get("chnames:{$this->id}:{$ch_key[2]}");
        }
        return $returned;
    }
    
    public function fetchChnames() {
        $this->character_names = $this->getChnames();
    }
    
    public function getLnames() {
        $lnames = $this->source->keys("lnames:{$this->id}:*");
        $returned = array();
        foreach($lnames as $l) {
            $lkey = explode(':', $l);
            $returned[$lkey[2]] = $this->source->get("lnames:{$this->id}:{$lkey[2]}");
        }
        return $returned;
    }
    
    public function getUnknownName($for_user_id) {
        
        $user_data = json_decode($this->source->get("characters:$for_user_id"),true);
        $age = self::START_AGE + Model_GameTime::formatDateTime($this->raw_time - $user_data['spawn_date'], 'y');
        if ($age >= 90) {
            $str = 'old';
        } else {
            $str = floor($age / 10) * 10;
        }
        
        return $user_data['sex'].':'.$str;
        
    }
    
    //returns list of available weapons from character inventory
    public function getWeaponsList() {
        
        $items = $this->source->smembers("char_items:{$this->id}");
        
        $weapon_list = array(
            
        );
        
        foreach ($items as $item_id) {
            $item = json_decode($this->source->get("global:items:$item_id"), true);
            $type = $item['type'];
            $item_type = json_decode($this->source->get("itemtype:$type"), true);
            if ($item_type['attack'] && !in_array($type, array_keys($weapon_list))) {
                $weapon_list[$type] = $item_type['attack'];
            }
        }
        $weapon_list[1] = 10;
        asort($weapon_list);
        
        $returned = array();
        
        foreach (array_keys($weapon_list) as $w) {
            $returned[] = $w;
        }
        //print_r($returned);
        
        return $returned;
            
    }
}

?>
