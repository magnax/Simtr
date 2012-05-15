<?php

class Model_Location_Redis extends Model_Location {

    public function findOneByID($location_id, $character_id) {

        if ($this->source->sismember('global:locations', $location_id)) {

            $this->id = $location_id;

            $data = json_decode($this->source->get("locations:$location_id"), true);

            $this->x = $data['x'];
            $this->y = $data['y'];
            $this->res_slots = $data['res_slots'];
            $this->used_slots = $data['used_slots'];
            $this->resources = $data['resources'];
            
            if ($character_id) {
                $name = $this->source->get("characters:$character_id:lnames:$location_id");
                $this->name = $name ? $name : 'Nienazwane miejsce';
            } else {
                $this->name = $data['name'];
            }

            return $this;
        } else {
            return null;
        }
    }

    public function appendCharacter($id) {
        $this->source->sadd("locations:{$this->id}:characters", $id);
    }

    private function getAllCharactersId() {
        //wszystkie id postaci należących do danej lokacji
        //łącznie z budynkami, pojazdami, statkami
        return $this->source->smembers("locations:{$this->id}:characters");

    }

    public function getAllHearableCharacters($as_array = false) {

        $all_chars_id = $this->getAllCharactersId();

        $hearable_chars = array();

        foreach ($all_chars_id as $char_id) {
            $tmp_char = Model_Character::getInstance($this->source)
                    ->fetchOne($char_id)
                    ->toArray();
            if (in_array($tmp_char['place_type'], $this->PLACE_HEARABLE)) {
                if ($as_array) {
                    $hearable_chars[] = $tmp_char;
                } else {
                    $hearable_chars[] = $tmp_char['id'];
                }
            }

        }

        return $hearable_chars;

    }

    /**
     * zwraca listę ID postaci znajdujących się w tym samym typie lokacji
     * @return <type> 
     */
    public function getAllVisibleCharacters($place_type) {

        $all_chars_id = $this->getAllCharactersId();

        $hearable_chars = array();

        foreach ($all_chars_id as $char_id) {
            $tmp_char = Model_Character::getInstance($this->source)
                    ->fetchOne($char_id)
                    ->toArray();
            if ($tmp_char['place_type'] == $place_type) {
                $hearable_chars[] = $tmp_char['id'];
            }

        }

        return $hearable_chars;

    }

    public function calculateUsedSlots() {
        $projects = Model_ProjectManager::getInstance(null, $this->source)
            ->find('loc', $this->id);
        $used_slots = 0;
        foreach ($projects as $p) {
            if ($p['type_id'] == 'get_raw') {
                $used_slots += $this->source->scard("projects:{$p['id']}:workers");
            }
        }
        $this->used_slots = $used_slots;
    }

    public function save() {

        if (!$this->id) {
            $this->id = $this->source->incr("global:IDLocation");
            $this->source->sadd("global:locations", $this->id);
        }

        $this->source->set("locations:{$this->id}", json_encode($this->toArray()));

    }

    public function saveProjects() {
        $this->source->multi();
        $this->source->del("locations:{$this->id}:projects");
        foreach ($this->projects as $p) {
            $this->source->sadd("locations:{$this->id}:projects", $p);
        }
        $this->source->exec();
    }

    public function getRaws() {

        $raws = $this->source->smembers("locations:{$this->id}:raws");
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

        //$tmp = array();

        $this->source->del("locations:{$this->id}:raws");

        foreach ($raws as $r) {
            $tmp = array($r['id']=>$r['amount']);
            $this->source->sadd("locations:{$this->id}:raws", json_encode($tmp));
        }
    }

    public function putRaw($id, $amount) {

        $raws = $this->getRaws();
        $raws[$id]['amount'] -= $amount;
        if ($raws[$id]['amount'] <= 0) {
            unset($raws[$id]);
        }
        $this->source->del("locations:{$this->id}:raws");

        foreach ($raws as $r) {
            $tmp = array($r['id']=>$r['amount']);
            $this->source->sadd("locations:{$this->id}:raws", json_encode($tmp));
        }

    }

    public function getAllLocations($exclude = null) {
        
        $all_locations = $this->source->smembers("global:locations");
        
        $returned = array();
        
        foreach ($all_locations as $location) {
            if ($location != $exclude) {
                $location_data = json_decode($this->source->get("locations:$location"), true);
                $returned[$location] = $location.' ('.$location_data['x'].','.$location_data['x'].')';
            }
        }
        
        return $returned;
    }

    public function getExits($lnames = null) {
         
        $exits = $this->source->smembers("locations:{$this->id}:exits");
        
        $returned = array();
         
        foreach ($exits as $e) {
            
            $road = Model_Road::getInstance($this->source)->findOneByID($e);
            $returned[] = array(
                'level'=>$road->getLevel(), 
                'lid'=>$road->getDestinationLocationID(), 
                'name'=>  $lnames ? $lnames->getName($road->getDestinationLocationID()) : Model_Location::getInstance($this->source)->findOneByID($road->getDestinationLocationID(), null)->getName(),
                'distance'=>$road->getDistance(),
                'direction'=>  Helper_Utils::getDirectionString($road->getDirection())
            );
         }
         
         return count($returned)? $returned : null;
    }
    
    public function addExit($exit_id) {
        $this->source->sadd("locations:{$this->id}:exits", $exit_id);
    }
    
}

?>
