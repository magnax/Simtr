<?php

/**
 * klasa postaci gracza
 */
abstract class Model_Character {

    const START_AGE = 20;
    protected $id = null;
    protected $name;
    protected $sex;
    protected $spawn_date;
    protected $spawn_location_id;
    protected $age;
    protected $user_id;
    protected $location_id;
    protected $eq_weight;
    protected $place_type;
    protected $place_id;
    protected $project_id;
    protected $travel_id;
    
    /**
     * współczynniki
     */
     protected $food;

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
    
    //private $spawn_day;

    public function  __construct($source) {
        $this->source = $source;
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
        return $this->name;
    }

    public function getSex() {
        return $this->sex;
    }

    public function getSpawnDate() {
        return $this->spawn_date;
    }

    public function countVisibleAge() {
        $age = $this->countAge();
        if ($age >= 90) {
            return 'old';
        } else {
            return floor($age / 10) * 10;
        }
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

    public function __get($name) {
        return $this->{$name};
    }

    public static function getInstance($source) {
        if ($source instanceof Predis_Client) {
            return new Model_Character_Redis($source);
        }
    }

    public function countAge() {
        return self::START_AGE + Model_GameTime::formatDateTime(Model_GameTime::getRawTime() - $this->spawn_date, 'y');
    }

    public function toArray() {
        return array(
            'id'=>$this->id,
            'name'=>$this->name,
            'age'=>$this->countAge(),
            'spawn_date'=>$this->spawn_date,
            'sex'=>$this->sex,
            'location_id'=>$this->location_id,
            'spawn_location_id'=>$this->spawn_location_id,
            'eq_weight'=>$this->eq_weight,
            'project_id'=>$this->project_id,
            'place_type'=>$this->place_type,
            'place_id'=>$this->place_id,
        );
    }

    abstract public function save();
    abstract public function fetchOne($id);
    abstract public function getInfo(array $characters);
    abstract public function getEvents();

}

?>
