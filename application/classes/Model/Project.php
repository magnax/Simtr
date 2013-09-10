<?php defined('SYSPATH') or die('No direct script access.');

class Model_Project extends OHM {

    protected $_columns = array('owner_id', 'type_id', 'location_id', 'time', 'time_elapsed', 'created_at', 'name');
    
    // typy projektów
    const TYPE_BUILD = 'Build'; //produkcja budynków
    const TYPE_BURY = 'Bury'; //zakopywanie ciał
    const TYPE_GET_RAW = 'GetRaw'; //wydobycie surowców z ziemi
    const TYPE_LOCKBUILD = 'Lockbuild'; //wstawianie i rozbudowa zamków
    const TYPE_MAKE = 'Make'; //produkcja przedmiotów
    
    protected static $checkSpecsArray = array('Make', 'Build', 'Lockbuild');

    /*
     * creates new project if id is null, if type is provided then creates 
     * project of this type
     * or returns existed project
     * 
     */
    public static function factory($type = null, $id = null) {
        
        if (is_null($type) && !is_null($id)) {
            $project = new Model_Project($id);
            $type = $project->type_id;
        }
        
        if ($type) {
            $class = 'Model_Project_'.ucfirst($type);
        } else {
            $class = 'Model_Project';
        }
        
        return new $class($id);
        
    }

    public function get_name() {
        
        return 'Dummy project name because of no type';
        
    }

    public function getPercent($accuracy = 0) {
        return round(($this->time_elapsed / $this->time * 100), $accuracy);
    }

    public function calculateProgress($decimals = 0) {
        
        if (in_array($this->type_id, self::$checkSpecsArray)) {    
            if (!$this->hasAllSpecs()) {
                return '-';
            }
        }
        
        return number_format(100 * $this->time_elapsed / $this->time, $decimals, ',', '') . '%';
        
    }

    public function addParticipant(Model_Character $character, $time) {

        $participants_key = "projects:{$this->id}:participants";
        
        $new_participant = array(
            'id'=>$character->getId(),
            'start'=>$time,
            'end'=>null,
            'factor'=>1 //na razie, w przyszłości będzie różny
        );

        $participants = RedisDB::getJSON($participants_key);
        $participants[] = $new_participant;
        RedisDB::setJSON($participants_key, $participants);
        
        RedisDB::sadd("projects:{$this->id}:workers", $character->id);
        
        RedisDB::set("characters:{$character->id}:current_project", $this->id);
        RedisDB::set("active_projects:{$this->id}", 1);

    }

    public function removeParticipant(Model_Character $character, $time) {

        $participants_key = "projects:{$this->id}:participants";
        
        $participants = RedisDB::getJSON($participants_key);
        RedisDB::srem("projects:{$this->id}:workers", $character->id);

        foreach ($participants as &$p) {
            if ($p['id'] == $character->id && !$p['end']) {
                $p['end'] = $time;
            }
        }

        RedisDB::setJSON($participants_key, $participants);
        
        if (!count(RedisDB::smembers("projects:{$this->id}:workers"))) {
            RedisDB::del("active_projects:{$this->id}");
        }

    }

    
    /**
     * gets already addes resources and items
     */    
    public function getRaws($simple = false) {
        
        return Model_Project_Raw::getRaws($this->id, $simple);
        
    }

    /**
     * gets all resources and items needed and already added to project
     * and calculates amounts
     */
    public function getAllSpecs() {
        
        $specs = $this->getSpecs(false);
        
        if ($specs) {
            $raws = $this->getRaws(true);

            $all_specs = array();

            foreach ($specs as $spec) {

                if (in_array($spec->resource_id, array_keys($raws))) {
                    $added = $raws[$spec->resource_id];
                } else {
                    $added = 0;
                }

                $all_specs[] = array(
                    'resource_id' => $spec->resource_id,
                    'name' => $spec->resource->name,
                    'needed' => $spec->amount,
                    'added' => $added
                );

            }

            return $all_specs;
        } else {
            return null;
        }
        
    }
    
    public function hasAllSpecs() {
        
        $specs = $this->getSpecs(true);
        
        if ($specs) {
            $raws = $this->getRaws(true);

            foreach ($specs as $spec_key => $spec_value) {

                if (!in_array($spec_key, array_keys($raws)) || 
                    $raws[$spec_key] < $spec_value) {
                    return false;
                } 

            }
        }

        return true;
    }
    
    public function addRaw($res_id, $amount) {
        
        $raw = ORM::factory('Project_Raw')
            ->where('project_id', '=', $this->id)
            ->and_where('resource_id', '=', $res_id)
            ->find();
        
        if ($raw->loaded()) {
            
            $raw->amount += $amount;
            $raw->save();
            return true;
            
        }
        
        return false;
        
    }

        /**
     * this method would be overriden in child classes
     * 
     * @return boolean
     */
    public function hasAllResources() {
        
        return true;
        
    }
    
    public function get_participants() {
        
        return RedisDB::getJSON("projects:{$this->id}:participants");
        
    }

    public function get_workers() {
        
        try {
            return RedisDB::smembers("projects:{$this->id}:workers");
        } catch (RedisException $e) {}
        
    }
    
    public function finish() {
        
        RedisDB::set("finished_projects:{$this->id}", 1);
        RedisDB::del("active_projects:{$this->id}");
        
    }

    public static function get_active_projects_ids() {
        
        $keys = RedisDB::keys("active_projects:*");
        
        array_walk($keys, function(&$v) {
            $v = str_replace('active_projects:', '', $v);
        });

        return $keys;
        
    }
    
}

?>
