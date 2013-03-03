<?php defined('SYSPATH') or die('No direct script access.');

class Model_Location extends ORM {  
    
    protected $_belongs_to = array(
        'town' => array(
            'model' => 'Town',
            'foreign_key' => 'location_id',
            'far_key' => 'id',
        ),
        'locationtype' => array(
            'model' => 'LocationType',
            'foreign_key' => 'locationtype_id',
            'far_key' => 'id',
        ),
        'locationclass' => array(
            'model' => 'LocationClass',
            'foreign_key' => 'id',
            'far_key' => 'class_id',
        ),
    );
    
    protected $_has_many = array(
        'characters' => array(
            'model' => 'Character',
            'foreign_key' => 'location_id',
            'far_key' => 'id'
        ),
        'resources' => array(
            'model' => 'Resource',
            'through' => 'locations_resources',
            'foreign_key' => 'location_id',
            'far_key' => 'resource_id'
        ),
    );
    
    protected $_has_one = array(
        'lock' => array(
            'model' => 'Lock',
            'foreign_key' => 'location_id',
            'far_key' => 'id'
        )
    );

    public function getCharacters(Model_Character $character = null) {
        $characters = $this->characters->find_all()->as_array();
        foreach($characters as &$ch) {
            $ch->chname = $character->getChname($ch->id);
        }
        return $characters;
    }

    public function getHearableCharacters() {
        
        $returned = array();
        //for now only, these are actually all visible characters
        $all = $this->characters->find_all()->as_array();
        foreach ($all as $character) {
            $returned[] = $character->id;
        }
        
        return $returned;
        
    }
    
    public function isHearable($character_id) {
        $all = $this->characters->find_all()->as_array('id', 'id');
        return in_array($character_id, $all);
    }

    public function getVisibleCharacters() {
        
        $returned = array();
        //for now only, these are actually all visible characters
        $all = $this->characters->find_all()->as_array();
        foreach ($all as $character) {
            $returned[] = $character->id;
        }
        
        return $returned;
        
    }
    
    public static function getRandomSpawnLocation() {
        $random_location = ORM::factory('location')
            ->where('locationtype_id', '=', 1)
            ->order_by(DB::expr('RAND()'))
            ->find();
        
        return $random_location;
    }
    
    public function addProject($project_id, $source) {
        
        $source->sadd("locations:{$this->id}:projects", $project_id);
        
    }
    
    /**
     * this method has to calculate count of free slots for 
     * gathering
     * 
     * @return integer count of free slots
     */
    public function countUsedSlots($source) {
        $projects = $source->smembers("locations:{$this->id}:projects");
        $participants_count = 0;
        foreach ($projects as $project) {
            $participants_count += count(json_decode($source->get("projects:{$project}:participants"), true));
        }
        return $participants_count;
    }
    
    public function getRaws() {

        $raws = RedisDB::getInstance()->getJSON("locations:{$this->id}:raws");
        
        $tmp = array();
        if ($raws) {
            foreach ($raws as $k => $v) {
                $resource = ORM::factory('resource', $k)->d;
                $tmp[$k] = array(
                    'id'=>$k,
                    'name'=>$resource,
                    'amount'=>$v
                );
            }
        }
        return $tmp;

    }

    public function addRaw($id, $amount) {
        
        $raws = RedisDB::getInstance()->getJSON("locations:{$this->id}:raws");
        
        if ($raws) {
            if (in_array($id, array_keys($raws))) {
                $raws[$id] += $amount;
            } else {
                $raws[$id] = $amount;
            }
        } else {
            $raws[$id] = $amount;
        }

        RedisDB::getInstance()->setJSON("locations:{$this->id}:raws", $raws);
        
    }
    
    public function putRaw($id, $amount) {

        $raws = RedisDB::getInstance()->getJSON("locations:{$this->id}:raws");
        
        if ($raws) {
            $raws[$id] -= $amount;
            if ($raws[$id] <= 0) {
                unset($raws[$id]);
            }
            
            RedisDB::getInstance()->setJSON("locations:{$this->id}:raws", $raws);
            
        }

    }
    
    public function getItems() {
        
        $items = RedisDB::getInstance()->smembers("loc_items:{$this->id}");
        
        $tmp = array();
        if ($items) {
            
            $db_items = ORM::factory('item')->where('id', 'IN', DB::expr('('. join(',',$items).')'))
                ->find_all()->as_array();
        
            foreach ($db_items as $item) {
                $tmp[] = array(
                    'id'=>$item->id,
                    'name'=>$item->itemtype->name,
                    'state'=>  Model_ItemType::getState($item->points/$item->itemtype->points)
                );
            }
            
        }
        
        return $tmp;
        
    }
    
    public function addItem($item_id) {
        
        RedisDB::getInstance()->sadd("loc_items:{$this->id}", $item_id);
        
    }

    public function putItem($item_id) {
        
        RedisDB::getInstance()->srem("loc_items:{$this->id}", $item_id);
        
    }
    
    public function getBuildings() {
        
        $returned = ORM::factory('location')
            ->where('locationtype_id', '=', '2')
            ->and_where('parent_id', '=', $this->id)
            ->find_all()
            ->as_array();
        
        return $returned;
    }
    
        public function getCorpses() {
        
        return ORM::factory('corpse')
            ->where('location_id', '=', $this->id)
            ->find_all();
        
    }
    
        public function getNotes() {
        
        $notes = RedisDB::getInstance()->smembers("locations:{$this->id}:notes");
        
        $tmp = array();
        if ($notes) {
            
            $db_notes = ORM::factory('note')->where('id', 'IN', DB::expr('('. join(',',$notes).')'))
                ->find_all()->as_array();
        
            foreach ($db_notes as $note) {
                $tmp[] = array(
                    'id'=>$note->id,
                    'title'=>$note->title,
                );
            }
            
        }
        
        return $tmp;
        
    }
    
    public function getProjectsIds() {
        
        return RedisDB::getInstance()->smembers("locations:{$this->id}:projects");
        
    }
    
    public function getLock() {
        
        return 0;
        
    }
    
    public function hasProjectType($type_id) {
        
        //check if there is project of lock making
        $projects_ids = $this->getProjectsIds();
        foreach ($projects_ids as $project_id) {
            $project = RedisDB::getJSON("projects:$project_id");
            if ($type_id == $project['type_id']) {
                return true;
            }
        }
        
        return false;
        
    }
    
}

?>
