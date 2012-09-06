<?php defined('SYSPATH') or die('No direct script access.');

class Model_Location extends ORM {  
    
    protected $_has_one = array(
        'town' => array(
            'model' => 'town',
            'foreign_key' => 'location_id',
            'far_key' => 'id',
        ),
        'locationtype' => array(
            'model' => 'locationtype',
            'foreign_key' => 'id',
            'far_key' => 'locationtype_id',
        ),
    );
    
    protected $_has_many = array(
        'characters' => array(
            'model' => 'character',
            'foreign_key' => 'location_id',
            'far_key' => 'id'
        ),
        'resources' => array(
            'model' => 'resource',
            'through' => 'locations_resources',
            'foreign_key' => 'location_id',
            'far_key' => 'resource_id'
        ),
    );
    
    public function getHearableCharacters() {
        
        $returned = array();
        //for now only, these are actually all visible characters
        $all = $this->characters->find_all()->as_array();
        foreach ($all as $character) {
            $returned[] = $character->id;
        }
        
        return $returned;
        
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
    
}

?>
