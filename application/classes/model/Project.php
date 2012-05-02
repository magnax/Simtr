<?php

abstract class Model_Project {

    // typy projektów
    const TYPE_GET_RAW = 'GetRaw'; //wydobycie surowców z ziemi
    const TYPE_MAKE = 'Make'; //produkcja przedmiotów

    //pola zapisywane metodą toArray()
    protected $id;
    protected $owner_id;
    protected $type_id;
    protected $place_type;
    protected $place_id;
    protected $time;
    protected $time_elapsed;
    protected $created_at;
    protected $name;

    //pozostałe pola
    protected $active;

    /**
     * zbiór uczestników projektu - do obliczania czasu
     * @var array
     */
    protected $participants = array();
    
    /**
     * zbiór pracujących postaci (id)
     * @var array
     */
    protected $workers = array();

    protected $source;

    public function  __construct($type, $source) {
        $this->type_id = $type;
        $this->source = $source;
    }
    
    public static function getInstance($type, $source) {

        $model = 'Model_Project_'.$type;
        return new $model($type, $source);

    }

    public function getPercent($accuracy = 0) {
        return round(($this->time_elapsed / $this->time * 100), $accuracy);
    }

    public function getSource() {
        return $this->source;
    }

    public function getId() {
        return $this->id;
    }

    public function  __set($name,  $value) {
        $this->$name = $value;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTypeId() {
        return $this->type_id;
    }

    public function getName() {
        return $this->name;
    }

    public function setActive($act) {
        $this->active = $act;
    }

    public function getActive() {
        return $this->active;
    }

    public function setParticipants(array $participants) {
        $this->participants = $participants;
    }

    public function getParticipants() {
        return $this->participants;
    }

    public function setWorkers($workers) {
        $this->workers = $workers;
    }

    public function getWorkers() {
        return $this->workers;
    }

    public function toArray() {
        return array(
            'id'=>$this->id,
            'name'=>$this->name,
            'owner_id'=>$this->owner_id,
            'type_id'=>$this->type_id,
            'place_type'=>$this->place_type,
            'place_id'=>$this->place_id,
            'time'=>$this->time,
            'time_elapsed'=>$this->time_elapsed,
            'created_at'=>$this->created_at
        );
    }

    public function addParticipant(Model_Character $character, $time) {

        $character->setProjectId($this->id);

        $new_participant = array(
            'id'=>$character->getId(),
            'start'=>$time,
            'end'=>null,
            'factor'=>1 //na razie, w przyszłości będzie różny
        );

        $this->participants[] = $new_participant;
        $this->workers[] = $character->getId();
        $this->active = true;

    }

    public function removeParticipant(Model_Character $character, $time) {

        $character->setProjectId($this->id);

        foreach ($this->participants as &$p) {
            if ($p['id'] == $character->getId() && !$p['end']) {
                $p['end'] = $time;
            }
        }

        $tmp_wrk = array();
        foreach ($this->workers as $w) {
            if ($w != $character->getId()) {
                $tmp_wrk[] = $w;
            }
        }
        $this->workers = $tmp_wrk;

    }

    public function set($data) {

        foreach ($data as $k=>$v) {
            $this->$k = $v;
        }

    }

    //abstract function save();
    //abstract public function find($place_type, $place_id);
    //abstract public function findOneById($id);
    //abstract public function addParticipant(Model_Character $character, $time);
    //abstract public function removeParticipant(Model_Character $character, $time);
    //abstract public function getName();

}

?>
