<?php defined('SYSPATH') or die('No direct script access.');
/**
 * klasa postaci gracza
 */
class Model_Character extends ORM {

    protected static $chname_link = '<a href="{{base}}chname?id=%d">%s</a>';

    protected $_belongs_to = array(
        'spawn_location' => array(
            'model' => 'Location',
            'foreign_key' => 'spawn_location_id',
            'far_key' => 'id'
        ),
        'location' => array(
            'model' => 'Location',
            'foreign_key' => 'location_id',
            'far_key' => 'id'
        )
    );

    protected $_has_many = array(
        'keys' => array(
            'model' => 'Key',
            'foreign_key' => 'character_id',
            'far_key' => 'id',
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
    const MAX_WEIGHT = 15000;

    private $_pagination = null;

    //memorized character name
    public $chname = null;

    public function getInfo($raw_time) {
        
        $name = $this->getChname($this->id);
        $location = new Model_Location($this->location_id);
        $spawn_location = new Model_Location($this->spawn_location_id);
        
        if ($location->parent_id) {
            $parent_location = ORM::factory('Location', $location->parent_id);
            $sublocation = $location->get_lname($this->id); //$location->name;
            $location_id = $parent_location->id;
            $location_name = $parent_location->get_lname($this->id); //ORM::factory('LName')->name($this->id, $parent_location->id)->name;
        } else {
            //is grand location
            $sublocation = null;
            $location_id = $location->id;
            $location_name = $location->get_lname($this->id);
        }
        
        $my_project_id = RedisDB::instance()->get("characters:{$this->id}:current_project");
        $my_project = ($my_project_id) ? RedisDB::instance()->getJSON("projects:$my_project_id") : null;

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
            'location' => $location_name,
            'spawn_location' => $spawn_location->get_lname($this->id),
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

    
    public function getUnknownName($char_id) {
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
    
    public function getChNameLink($dest_character_id) {
        return sprintf(str_replace('{{base}}', URL::base(), self::$chname_link), $dest_character_id, $this->getChname($dest_character_id));
    }

    /**
     * 
     * get other character remembered name
     * 
     * @param integer $dest_character_id
     * @return string name of character or unknown character string
     */
    public function getChname($dest_character_id) {
        
        $name = ORM::factory('ChName')->name($this->id, $dest_character_id)->name;
        if (!$name) {
            $name = ($this->id == $dest_character_id) 
                ? $this->name 
                : $this->getUnknownName($dest_character_id);
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

    public function connectedChar() {
        return RedisDB::get("connected_char:{$this->id}");
    }

    public function connectedUser() {
        return RedisDB::get("connected_user:{$this->user_id}");
    }
    
    /**
     * calculates character inventory weight
     * 
     * @return int character inventory total weight (raws, items, keys)
     */
    public function calculateWeight() {
        
        //raws... and only raws for a while ;-)
        $raws = RedisDB::getJSON("raws:{$this->id}");
        
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
     * 
     * @todo make user resources hash instead of json'ed array
     */
    public function getRaws($simple = false) {

        $raws = RedisDB::getJSON("raws:{$this->id}");
        
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
    
    public function getRawAmount($raw_id) {
    
        $raws = RedisDB::getJSON("raws:{$this->id}");
        
        foreach ($raws as $k => $v) {
            if ($k == $raw_id) {
                return $v;
            }
        }
        
        return 0;
        
    }

    public function addRaw($id, $amount) {
        
        $raws = RedisDB::getJSON("raws:{$this->id}");
        
        if ($raws) {
            if (in_array($id, array_keys($raws))) {
                $raws[$id] += $amount;
            } else {
                $raws[$id] = $amount;
            }
        } else {
            $raws[$id] = $amount;
        }

        RedisDB::setJSON("raws:{$this->id}", $raws);
        
    }
    
    public function putRaw($id, $amount) {

        $raws = RedisDB::getJSON("raws:{$this->id}");
        
        if ($raws && isset($raws[$id])) {
            $raws[$id] -= $amount;
            if ($raws[$id] <= 0) {
                unset($raws[$id]);
            }
            
            RedisDB::setJSON("raws:{$this->id}", $raws);
            
        } else {
            throw new Exception('Nieprawidłowy materiał: '.$id);
        }

    }
    
    public function get_raw_from_location(Model_Location $location, $resource_id, $amount) {
        
        if (!is_integer($amount) || ($amount <= 0)) {
            throw new Exception('Nieprawidłowa ilość');
        }
        
        $location_raws = $location->getRaws(true);
        
        if (!isset($location_raws[$resource_id])) {
            throw new Exception('Nieprawidłowy materiał');
        }
        
        if ($amount > $location_raws[$resource_id]) {
            throw new Exception('Nieprawidłowa ilość');
        }
        
        if (($amount + $this->calculateWeight()) > Model_Character::MAX_WEIGHT) {
            throw new Exception('Nie możesz tyle unieść');
        }
            
        //all ok
        $location->putRaw($resource_id, $amount);
        $this->addRaw($resource_id, $amount);
        
    }

    
    public function get_note_from_location(Model_Location $location, Model_Note $note) {
        
        RedisDB::srem("locations:{$location->id}:notes", $note->id);
        RedisDB::sadd("notes:{$this->id}", $note->id);
        
    }
    
    public function put_note_to_location(Model_Location $location, Model_Note $note) {
        
        RedisDB::srem("notes:{$this->id}", $note->id);
        RedisDB::sadd("locations:{$location->id}:notes", $note->id);
        
    }

    public function give_raw_to_character($character_id, $resource_id, $amount) {
        
        
        if (!is_numeric($amount) || ($amount <= 0)) {
            throw new Exception('Nieprawidłowa liczba: >>' . $amount . '<<');
        }
        
        $amount = (int) $amount;
        $raws = $this->getRaws(true);
        
        if (!isset($raws[$resource_id])) {
            throw new Exception('Nieprawidłowy materiał');
        }
        
        if ($amount > $raws[$resource_id]) {
            throw new Exception('Nieprawidłowa ilość');
        }
        
        $dest_character = ORM::factory('Character', $character_id);
         
        if (!$dest_character->loaded() || !$this->location->isHearable($dest_character)) {
            throw new Exception('Tej osoby już tutaj nie ma');
        }
        
        if (($amount + $dest_character->calculateWeight()) > Model_Character::MAX_WEIGHT) {
            throw new Exception('Odbiorca nie może tyle unieść');
        }
            
        //all ok
        $this->putRaw($resource_id, $amount);
        $dest_character->addRaw($resource_id, $amount);
        
    }

    public function add_raw_to_project(Model_Project $project, $resource_id, $amount) {
        
        if (!is_numeric($amount) || ($amount <= 0)) {
            throw new Exception('Nieprawidłowa liczba: >>' . $amount . '<<');
        }
        
        $amount = (int) $amount;
        $raws = $this->getRaws(true);
        
        if (!isset($raws[$resource_id])) {
            throw new Exception('Nieprawidłowy materiał');
        }
        
        if ($amount > $raws[$resource_id]) {
            throw new Exception('Nieprawidłowa ilość');
        }
        
        //odjęcie postaci podanej ilości materiału
        $this->putRaw($resource_id, $amount);
        $project->addRaw($resource_id, $amount);
        
    }
    
    public function drop_item(Model_Item $item, Model_Location $location) {
        
        if (!$item->loaded()) {
            throw new Exception('Nieprawidłowy przedmiot');
        }
        
        if (!$this->hasItem($item->id)) {
            throw new Exception('Nie posiadasz tego przedmiotu');
        }
        
        $this->putItem($item->id);
        $location->addItem($item->id);
        
    }
    
    /**
     * 
     * get item from the ground (location objects) to character inventory
     * 
     * @param Model_Item $item
     * @param Model_Location $location
     * @throws Exception
     */
    public function get_item(Model_Item $item, Model_Location $location) {
        
        if (!$item->loaded()) {
            throw new Exception('Nieprawidłowy przedmiot');
        }
        
        if (!$location->hasItem($item->id)) {
            throw new Exception('Ten przedmiot nie znajduje się w lokacji');
        }
        
        $location->putItem($item->id);
        $this->addItem($item->id);
        
    }
    
    public function give_item(Model_Item $item, Model_Character $recipient) {
        
        if (!$recipient->loaded() || !$this->location->isHearable($recipient->id)) {
            throw new Exception('Tej osoby już tu nie ma');
        }
        
        if (!$this->hasItem($item->id)) {
            throw new Exception('Nie posiadasz tego przedmiotu!');
        }
        
        $this->putItem($item->id);
        $recipient->addItem($item->id);

    }

    

    public function getItems() {
        
        $items = RedisDB::smembers("items:{$this->id}");
        
        $tmp = array();
        if ($items) {
            $db_items = ORM::factory('Item')->where('id', 'IN', DB::expr('('. join(',',$items).')'))
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
    
    public function hasRaw($id) {
        return in_array($id, $this->getRaws(TRUE));
    }

    public function addItem($item_id) {
        
        RedisDB::sadd("items:{$this->id}", $item_id);
        
    }
    
    public function putItem($item_id) {
        
        RedisDB::srem("items:{$this->id}", $item_id);
        
    }
    
    //returns list of available weapons from character inventory
    public function getWeaponsList() {
        
        $items = RedisDB::smembers("items:{$this->id}");
        $weapon_list = array();
        
        if (count($items)) {
            $weapons = ORM::factory('Item')
                ->with('itemtype')
                ->where('item.id', 'IN', DB::expr('('. join(',',$items).')'))
                ->and_where('itemtype.attack', 'is not', NULL)
                ->order_by('itemtype.attack', 'desc')
                ->find_all()
                ->as_array();

            foreach ($weapons as $weapon) {
                if (!in_array($weapon->itemtype->id, array_keys($weapon_list))) {
                    $weapon_list[$weapon->itemtype->id] = $weapon->itemtype->name;
                }
            }
        }
        
        $weapon_list[0] = 'goła pięść';
        
        return $weapon_list;
            
    }
    
    /**
     * 
     * @todo this method doesn't really belong to character, it's a helper method
     * 
     * @param type $skill
     * @return string
     */
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
        
        $notes = RedisDB::smembers("notes:{$this->id}");
        
        $tmp = array();
        if ($notes) {
            $tmp = ORM::factory('Note')->where('id', 'IN', DB::expr('('. join(',',$notes).')'))
                ->find_all()->as_array();
        }
        return $tmp;
        
    }
    
    public function leaveCurrentProject($raw_time) {
        
        $project_id = RedisDB::get("characters:{$this->id}:current_project");
        
        if ($project_id) {
            $manager = Model_ProjectManager::getInstance(null, RedisDB::instance())
                ->findOneById($project_id);

            $manager->removeParticipant($this, $raw_time);
            $manager->save();

            RedisDB::del("characters:{$this->id}:current_project");
        }
        
    }
    
    public function enter_location(Model_Location $dest_location) {
        
        if (!$dest_location->loaded()) {
            throw new Exception('Nieprawidłowe miejsce');
        }
        
        $lock = $this->location->lock;
        
        if ($lock->locked && !$this->character->hasKey($lock->nr)) {
            throw new Exception('Nie możesz wyjść, zamknięte');
        }
        
        if ($dest_location->lock->locked && !$this->character->hasKey($dest_location->lock->nr)) {
            throw new Exception('Nie możesz wejść, zamknięte');
        }
        
        $gametime = new Model_GameTime(Kohana::$config->load('general.paths.time_daemon_path'));
        //if user is working on the project, leave it before enter
        $this->leaveCurrentProject($gametime->getRawTime());
        
        $this->location_id = $dest_location->id;
        $this->save();
        
    }

    public function hasItem($item_id) {
        $items = RedisDB::smembers("items:{$this->id}");
        return in_array($item_id, $items);
    }
    
    public function giveItemTo($item_id, $character_id) {
        
        RedisDB::srem("items:{$this->id}", $item_id);
        RedisDB::sadd("items:$character_id", $item_id);
        
    }

    public function charactersSelect(array $characters_ids, $with_self = false) {
        
        $returned = array();
        foreach ($characters_ids as $ch) {
            if ($ch != $this->id || $with_self) {
                $returned[$ch] = $this->getChname($ch);
            }
        }
        return $returned;
        
    }
    
    public function getEvents($page = 1) {
        
        //ile na stronę 
        $pagesize = 20;
        $size = RedisDB::llen("characters:{$this->id}:events");
        if ($size) {
            $from = ($page - 1) * $pagesize;
            $events = RedisDB::lrange("characters:{$this->id}:events", $from, $from + $pagesize - 1);
        } else {
            return array();
        }
        $return_events = array();

        foreach ($events as $id_event) {
            
            $event = new Model_Event($id_event);
            
            $return_events[] = $event->format_output($this, $id_event);

        }
        
        $this->_setPagination($from, $page, $size, $pagesize);
        
        return $return_events;
        
    }
    
    private function _setPagination($from, $page, $size, $pagesize) {
        $prev = ($page > 1) ? $page - 1 : null;
        $next = ($from + $pagesize < $size) ? $page + 1 : null;
        if ($prev || $next) {
            $this->_pagination = array(
                'prev' => $prev,
                'next' => $next
            );
        } else {
            $this->_pagination = NULL;
        }
    }

    public function getPagination() {
        return $this->_pagination;
    }

    public static function getAllCharactersIds() {
        
        return array_keys(ORM::factory('Character')->find_all()->as_array('id', 'id'));
        
    }
    
    public function hasKey($lock_nr) {
        
        return $this->keys->where('nr', '=', $lock_nr)->find()->loaded();
        
    }
    
}

?>
