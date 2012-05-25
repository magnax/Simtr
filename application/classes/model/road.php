<?php

abstract class Model_Road {
    
    //fields
    protected $id = null;
    protected $start_location_id;
    protected $end_location_id;
    protected $distance;
    protected $direction;
    protected $level;

    protected $source;

    public function  __construct($source) {
        $this->source = $source;
    }
    
    public static function getInstance($source) {
        if ($source instanceof Predis_Client) {
            return new Model_Road_Redis($source);
        }
    }
    
    public function getID() {
        return $this->id;
    }

    public function getLevel() {
        return $this->level;
    }
    
    public function getLevelString() {
        $levels = $this->getLevels();
        return $levels[$this->level];
    }


    public function getDistance() {
        return $this->distance;
    }
    
    public function getDirection() {
        return $this->direction;
    }
    
    public function getDestinationLocationID() {
        return $this->end_location_id;
    }
    
    public function setDistance($d) {
        $this->distance = $d;
    }

    public function setDirection($dir) {
        $this->direction = $dir;
    }
    
    public function setLevel($level) {
        $this->level = $level;
    }
    
    public function setLocations($start, $end) {
        $this->start_location_id = $start;
        $this->end_location_id = $end;
    }


    public function toArray() {
        return array(
            'id'=>$this->id,
            'start'=>$this->start_location_id,
            'end'=>$this->end_location_id,
            'distance'=>$this->distance,
            'direction'=>$this->direction,
            'level'=>$this->level
        );
    }

    abstract public function getLevels();
    abstract public function save();
    
}

?>
