<?php defined('SYSPATH') or die('No direct script access.');

/**
 * klasa postaci gracza
 */
class Model_Character extends ORM {

    protected $_belongs_to = array(
        'spawn_location' => array(
            'model' => 'location',
            'foreign_key' => 'spawn_location_id',
            'far_key' => 'id'
        ),
        'location' => array(
            'model' => 'location',
            'foreign_key' => 'location_id',
            'far_key' => 'id'
        )
    );


    public function rules() {
        return array(
            'name'=>array(
                array('not_empty'),
            ),
            'sex'=>array(
                array(function($value, Validation $obj) {
                    if (!in_array($value, array('K','M'))) {
                        $obj->error('sex', 'not_valid');
                    }
                }, array(':value', ':validation')),
            )
        );
    }

    const START_AGE = 20;
    
    protected $spawn_date;
    protected $age;
    protected $eq_weight;
    protected $place_type;
    protected $place_id;
    protected $project_id;
    protected $travel_id;
    
    /**
     * współczynniki
     */
    
     //jedzenie (0-100)
     protected $food;
     
     //żywotność (800-1200) 
     protected $vitality;

     //siła (0.6 ... 1.8)
     protected $strength;

     /**
      * kolekcje
      */
     protected $items = array(); //asocjacyjna ID:stan, np: 928:98
     protected $resources = array(); //asocjacyjna np: RES_SAND : 20000
     protected $events = array(); //osobna struktura

    /**
     * źródło danych (Redis, RDBMS, other)
     */
    protected $source;

    protected $chnames;

    //memorized characters names
    public $character_names = null;
    public $location_names = null;
    
    public $raw_time;
    
    public $lang = 'pl';

    public function setSource($source) {
        $this->source = $source;
    }

    public function getInfo($raw_time) {
        
        $name = ORM::factory('chname')->name($this->id, $this->id)->name;
        $location = ORM::factory('location', $this->location_id);
        
        if ($location->parent_id) {
            $parent_location = ORM::factory('location', $location->parent_id);
            $sublocation = $location->name;
            $location_id = $parent_location->id;
            $location_name = ORM::factory('lname')->name($this->id, $parent_location->id)->name;
        } else {
            //is grand location
            $sublocation = null;
            $location_id = $location->id;
            $location_name = ORM::factory('lname')->name($this->id, $this->location_id)->name;
        }
        
        $spawn_location_name = ORM::factory('lname')->name($this->id, $this->spawn_location_id)->name;
        $my_project_id = RedisDB::get("characters:{$this->id}:current_project");
        $my_project = ($my_project_id) ? RedisDB::getJSON("projects:$my_project_id") : null;

        if ($my_project) {
            if (!$my_project['time_elapsed']) {
                $my_project['time_elapsed'] = 0;
            }
            $my_project['percent'] = number_format($my_project['time_elapsed'] / $my_project['time'] * 100, 2);
            $my_project['time_zero'] = $raw_time;
            $my_project['speed'] = 1; //for now, will be calculated
            
            $project_name = Model_Project::getInstance($my_project['type_id'])
                ->name($my_project, $this->id);
        }
        
        return array(
            'id' => $this->id,
            'name' => $name ? $name : $this->name,
            'age' => $this->countRealAge($raw_time),
            'spawn_day' => $this->created,
            'location_id' => $location_id,
            'spawn_location_id' => $this->spawn_location_id,
            'location' => Utils::getLocationName($location_name),
            'spawn_location' => ($spawn_location_name) ? $spawn_location_name : 'unknown location',
            'sublocation' => $sublocation,
            'sublocation_id' => null, //for now, later it may be ie. vehicle
            'life' => $this->life,
            'fed' => $this->fed,
            'strength' => 1.2,
            'fighting' => $this->fighting,
            'eq_weight' => $this->calculateWeight(),
            'project_id' => ($my_project_id) ? $my_project_id : 0,
            'myproject' => $my_project,
            'project_name' => $my_project_id ? $project_name : '',
        );
    }

    
    public function getUnknownName($char_id, $lang) {
        return 'nieznany ktoś';
    }

    public function setIDUser($id) {
        $this->user_id = $id;
    }

    public function setSex($sex) {
        $this->sex = $sex;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setIDLocation($id) {
        $this->location_id = $id;
    }

    public function setProjectId($id) {
        $this->project_id = $id;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return ($this->name);
    }

    public function getSex() {
        return $this->sex;
    }

    public function getSpawnDate() {
        return $this->spawn_date;
    }

    public function countVisibleAge($raw_time) {
        $age = $this->countAge($raw_time);
        if ($age >= 90) {
            return 'old';
        } else {
            return floor($age / 10) * 10;
        }
    }
    
    public function countRealAge($raw_time) {
        $raw = $raw_time - $this->created;
        return self::START_AGE+Model_GameTime::formatDateTime($raw, 'y');
    }

    public function getIDLocation() {
        return $this->location_id;
    }

    public function getPlaceType() {
        return $this->place_type;
    }

    public function getPlaceId() {
        return $this->place_id;
    }



    public function countAge($raw_time) {
        return self::START_AGE + Model_GameTime::formatDateTime($raw_time - $this->spawn_date, 'y');
    }

    public function toArray() {
        
        return array(
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'spawn_date' => $this->spawn_date,
            'sex' => $this->sex,
            'location_id' => $this->location_id,
            'spawn_location_id' => $this->spawn_location_id,
            'eq_weight' => $this->eq_weight,
            'project_id' => $this->project_id,
            'place_type' => $this->place_type,
            'place_id' => $this->place_id,
            'vitality' => $this->vitality,
            'life' => $this->life,
            'strength' => $this->strength,
            'fighting' => $this->fighting
        );
        
    }

    public function createNew($data) {

        $this->spawn_date = $data['spawn_date'];
        $this->spawn_location_id = $data['location_id'];
        $this->location_id = $data['location_id'];
        $this->eq_weight = 0;
        $this->place_type = 'loc';
        $this->place_id = $data['location_id'];
        $this->name = $data['name'];
        $this->sex = $data['sex'];
        $this->user_id = $data['user_id'];
        
        $this->save();
        
        return $this;

    }
    
    public function getChname($dest_character_id) {
        $name = ORM::factory('chname')->name($this->id, $dest_character_id)->name;
        if (!$name) {
            $name = ($this->id == $dest_character_id) 
                ? $this->name 
                : $this->getUnknownName($dest_character_id, $this->lang);
        }
        return $name;
    }

    public function getLname($for_location_id) {
        if ($this->location_names && isset($this->location_names[$for_location_id])) {
            return $this->location_names[$for_location_id];
        }
        return null;
    }

    public function isDying() {
        return ($this->life <= 0);
    }

    public function setDamage($points) {
        $this->life -= $points;
        $this->save();
    }

    public function connectedChar($redis) {
        return $redis->get("connected_char:{$this->id}");
    }

    public function connectedUser($redis) {
        return $redis->get("connected_user:{$this->user_id}");
    }
    
    /**
     * calculates character inventory weight
     * 
     * @return int character inventory total weight (raws, items, keys)
     */
    public function calculateWeight() {
        
        //raws... and only raws for a while ;-)
        $raws = RedisDB::getInstance()->getJSON("raws:{$this->id}");
        
        $weight = 0;
        
        if ($raws) {
            foreach ($raws as $k => $v) {
                $weight += $v;
            }
        }
        
        return $weight;
        
    }

        /**
     * gets inventory raws for character
     */
    public function getRaws() {

        $raws = RedisDB::getInstance()->getJSON("raws:{$this->id}");
        
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
        
        $raws = RedisDB::getInstance()->getJSON("raws:{$this->id}");
        
        if ($raws) {
            if (in_array($id, array_keys($raws))) {
                $raws[$id] += $amount;
            } else {
                $raws[$id] = $amount;
            }
        } else {
            $raws[$id] = $amount;
        }

        RedisDB::getInstance()->setJSON("raws:{$this->id}", $raws);
        
    }
    
    public function putRaw($id, $amount) {

        $raws = RedisDB::getInstance()->getJSON("raws:{$this->id}");
        
        if ($raws) {
            $raws[$id] -= $amount;
            if ($raws[$id] <= 0) {
                unset($raws[$id]);
            }
            
            RedisDB::getInstance()->setJSON("raws:{$this->id}", $raws);
            
        }

    }
    
    public function getItems() {
        
        $items = RedisDB::getInstance()->smembers("items:{$this->id}");
        
        $tmp = array();
        if ($items) {
            $db_items = ORM::factory('item')->where('id', 'IN', DB::expr('('. join(',',$items).')'))
                ->find_all()->as_array();
        
            foreach ($db_items as $item) {
                $tmp[] = array(
                    'id'=>$item->id,
                    'name'=>$item->itemtype->name,
                    'state'=>  Model_ItemType::getState($item->points/$item->itemtype->points, $item->itemtype->kind)
                );
            }
        }
        return $tmp;
        
    }
    
    public function addItem($item_id) {
        
        RedisDB::getInstance()->sadd("items:{$this->id}", $item_id);
        
    }
    
    public function putItem($item_id) {
        
        RedisDB::getInstance()->srem("items:{$this->id}", $item_id);
        
    }
    
    //returns list of available weapons from character inventory
    public function getWeaponsList() {
        
        $items = RedisDB::getInstance()->smembers("items:{$this->id}");
        
        if (count($items)) {
            $weapons = ORM::factory('item')
                ->with('itemtype')
                ->where('item.id', 'IN', DB::expr('('. join(',',$items).')'))
                ->and_where('itemtype.attack', 'is not', NULL)
                ->order_by('itemtype.attack', 'desc')
                ->find_all()
                ->as_array();

            $weapon_list = array();

            foreach ($weapons as $weapon) {
                if (!in_array($weapon->itemtype->id, array_keys($weapon_list))) {
                    $weapon_list[$weapon->itemtype->id] = $weapon->itemtype->name;
                }
            }
        }
        
        $weapon_list[0] = 'goła pięść';
        arsort($weapon_list);
        
        return $weapon_list;
            
    }
    
    public static function getSkillString($skill) {
        if ($skill >= 1.2) {
            return 'po mistrzowsku';
        } elseif ($skill >= 1.1) {
            return 'umiejętnie';
        } elseif ($skill >= 1) {
            return 'przeciętnie';
        } elseif ($skill >= 0.9) {
            return 'po amatorsku';
        } else {
            return 'niezręcznie';
        }
    }
    
    public function getNotes() {
        
        $notes = RedisDB::getInstance()->smembers("notes:{$this->id}");
        
        $tmp = array();
        if ($notes) {
            $tmp = ORM::factory('note')->where('id', 'IN', DB::expr('('. join(',',$notes).')'))
                ->find_all()->as_array();
        }
        return $tmp;
        
    }
    
    public function leaveCurrentProject($redis, $raw_time) {
        
        $project_id = RedisDB::get("characters:{$this->id}:current_project");
        
        if ($project_id) {
            $manager = Model_ProjectManager::getInstance(null, $redis)
                ->findOneById($project_id);

            $manager->removeParticipant($this, $raw_time);
            $manager->save();

            RedisDB::getInstance()->del("characters:{$this->id}:current_project");
        }
        
    }
    
    public function hasItem($item_id) {
        $items = RedisDB::getInstance()->smembers("items:{$this->id}");
        return in_array($item_id, $items);
    }
    
    public function giveItemTo($item_id, $character_id) {
        
        RedisDB::getInstance()->srem("items:{$this->id}", $item_id);
        RedisDB::getInstance()->sadd("items:$character_id", $item_id);
        
    }

    public function charactersSelect(array $characters_ids, $with_self = false) {
        
        $returned = array();
        foreach ($characters_ids as $ch) {
            if ($ch != $this->id || $with_self) {
                $name = ORM::factory('chname')->name($this->id, $ch)->name;
                if (!$name) {
                    $name = $this->getUnknownName($ch, $this->lang);
                }
                $returned[$ch] = $name;
            }
        }
        return $returned;
        
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

        $event_dispatcher = Model_EventDispatcher::getInstance($this->source, $this->lang);

        foreach ($events as $id_event) {

            $return_events[] = $event_dispatcher->formatEvent($id_event, $this->id);
            
        }
        
        //"pagination" ;) just info 
        $return_events[] = array(
            'id' => -1,
            'date' => '',
            'prev' => ($page > 1) ? $page - 1 : '',
            'current' => $page,
            'next' => ($from + $pagesize < $size) ? $page + 1 : '',
        );
        
        return $return_events;
    }
    
}

?>
