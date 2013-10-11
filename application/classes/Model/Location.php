<?php defined('SYSPATH') or die('No direct script access.');

class Model_Location extends ORM {  
    
    const TYPE_TOWN = 1;
    const TYPE_BUILDING = 2;
    const TYPE_ROAD = 3;

    protected $_belongs_to = array(
        
        'locationtype' => array(
            'model' => 'LocationType',
            'foreign_key' => 'locationtype_id',
            'far_key' => 'id',
        ),
        'locationclass' => array(
            'model' => 'LocationClass',
            'foreign_key' => 'class_id',
            'far_key' => 'id',
        ),
        'parent' => array(
            'model' => 'Location',
            'foreign_key' => 'parent_id',
            'far_key' => 'id',
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
        'machines' => array(
            'model' => 'Machine',
            'foreign_key' => 'location_id',
            'far_key' => 'id'
        ),
    );
    
    protected $_has_one = array(
        'lock' => array(
            'model' => 'Lock',
            'foreign_key' => 'location_id',
            'far_key' => 'id'
        ),
        'town' => array(
            'model' => 'Town',
            'foreign_key' => 'location_id',
            'far_key' => 'id',
        ),
    );

    public function get_characters(Model_Character $character) {
        $characters = $this->characters->find_all()->as_array();
        foreach($characters as &$ch) {
            $ch->chname = $character->getChname($ch->id);
        }
        return $characters;
    }

    public function get_hearable_characters_names(Model_Character $character, $remove_self = true) {
        $characters = $this->characters->find_all()->as_array('id', 'id');
        foreach($characters as $k => $v) {
            $characters[$k] = $character->getChname($k);
        }
        if ($remove_self) {
            unset($characters[$character->id]);
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
        $random_location = ORM::factory('Location')
            ->where('locationtype_id', '=', 1)
            ->order_by(DB::expr('RAND()'))
            ->find();
        
        return $random_location;
    }
    
    public function add_project($project_id) {
        
        RedisDB::sadd("locations:{$this->id}:projects", $project_id);
        
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
    
    public function getRaws($simple = false) {

        $raws = RedisDB::getJSON("locations:{$this->id}:raws");
        
        if ($simple) {
            return $raws;  
        } else {
            $tmp = array();
            if ($raws) {
                foreach ($raws as $k => $v) {
                    $resource = ORM::factory('Resource', $k)->d;
                    $tmp[$k] = array(
                        'id'=>$k,
                        'name'=>$resource,
                        'amount'=>$v
                    );
                }
            }
            return $tmp;
        }

    }

    public function addRaw($id, $amount) {
        
        $raws = RedisDB::getJSON("locations:{$this->id}:raws");
        
        if ($raws) {
            if (in_array($id, array_keys($raws))) {
                $raws[$id] += $amount;
            } else {
                $raws[$id] = $amount;
            }
        } else {
            $raws[$id] = $amount;
        }

        RedisDB::setJSON("locations:{$this->id}:raws", $raws);
        
    }
    
    public function putRaw($id, $amount) {

        $raws = RedisDB::getJSON("locations:{$this->id}:raws");
        
        if ($raws) {
            $raws[$id] -= $amount;
            if ($raws[$id] <= 0) {
                unset($raws[$id]);
            }
            
            RedisDB::setJSON("locations:{$this->id}:raws", $raws);
            
        }

    }
    
    public function getItems() {
        
        $items = RedisDB::smembers("loc_items:{$this->id}");
        
        $tmp = array();
        if ($items) {
            
            $db_items = ORM::factory('Item')->where('id', 'IN', DB::expr('('. join(',',$items).')'))
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
        
        RedisDB::sadd("loc_items:{$this->id}", $item_id);
        
    }

    public function putItem($item_id) {
        
        RedisDB::srem("loc_items:{$this->id}", $item_id);
        
    }
    
    /**
     * 
     * checks if given item belongs to location
     * 
     * @param type $item_id
     * @return type
     */
    public function hasItem($item_id) {
        
        $items = RedisDB::smembers("loc_items:{$this->id}");
        return in_array($item_id, $items);
        
    }
    
    public function getBuildings() {
        
        $returned = ORM::factory('Location')
            ->where('locationtype_id', '=', '2')
            ->and_where('parent_id', '=', $this->id)
            ->find_all()
            ->as_array();
        
        return $returned;
    }
    
        public function getCorpses() {
        
        return ORM::factory('Corpse')
            ->where('location_id', '=', $this->id)
            ->find_all();
        
    }
    
        public function getNotes() {
        
        $notes = RedisDB::smembers("locations:{$this->id}:notes");
        
        $tmp = array();
        if ($notes) {
            
            $db_notes = ORM::factory('Note')->where('id', 'IN', DB::expr('('. join(',',$notes).')'))
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
        
        return RedisDB::smembers("locations:{$this->id}:projects");
        
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
    
    /**
     * 
     * get other location remembered name
     * 
     * @param integer $character_id
     * @return string name of location or unknown location string
     */
    public function get_lname($character_id) {
        
        $name = ORM::factory('LName')->name($character_id, $this->id)->name;
        if (!$name) {
            return $this->getUnknownName();
        }
        return $name;
        
    }
    
    public function getUnknownName() {
        return ($this->parent_id) ? $this->name : 'nienazwane miejsce';
    }
    
    public function get_exits(Model_Character $character) {
        //for now just one fixed road from database to show result
        $roads = array(ORM::factory('Road', 1));
        $returned = array();
        foreach ($roads as $road) {
            $destination_id = ($road->location_1_id == $this->id) ? $road->location_2_id : $road->location_1_id;
            $destination = new Model_Location($destination_id);
            $can_be_upgraded = $road->can_be_upgraded();
            $returned[] = array(
                'id' => $road->id,
                'destination_id' => $destination->id,
                'destination_name' => $destination->get_lname($character->id),
                'level' => $road->get_level_name(),
                'direction' => Utils::getDirectionString(Utils::calculateDirection($this->town->x, $this->town->y, $destination->town->x, $destination->town->y)),
                'can_be_upgraded' => $can_be_upgraded,
            );
        }
        return $returned;
        
    }
    
    public function remove_project($project_id) {
        
        RedisDB::srem("locations:{$this->id}:projects", $project_id);
        
    }

    public function show_roads() {
        
        return ($this->locationtype->id == self::TYPE_TOWN || $this->locationtype->movable);
        
    }
    
    public static function get_possible_parent_locations() {
        
        return array('' => ' - nadrzędna - ') + ORM::factory('Location')
            ->where('locationtype_id', 'IN', DB::expr('(1,2)'))
            ->find_all()->as_array('id', 'name');
        
    }
    
    public function get_detail_object() {
        
        $class_name = ORM::factory('LocationType', $this->locationtype_id)->name;
        
        return ORM::factory(ucfirst($class_name), array('location_id' => $this->id));
        
    }
    
    public function is_town() {
        
        return $this->locationtype_id == self::TYPE_TOWN;
        
    }
    
    public static function get_towns() {
        
        return ORM::factory('Location')
            ->where('locationtype_id', '=', self::TYPE_TOWN)
            ->find_all()
            ->as_array('id', 'name');
        
    }

    /**
     * true if it's possible in this location to work and build machines
     * 
     * @return boolean
     */
    public function is_workable() {
        
        return $this->locationtype->workable;
        
    }
    
    
    
}

?>
