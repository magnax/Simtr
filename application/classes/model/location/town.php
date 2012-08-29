<?php defined('SYSPATH') or die('No direct script access.');

abstract class Model_Location_Town extends Model_Location {

    //max slots for gathering resources
    //number of used slots is calculated
    protected $res_slots;
    
    //town coordinates on map
    protected $x;
    protected $y;


    //raw resources list
    public $raws = array();
    
    //projects list
    protected $projects = array();
    
    //resources list
    protected $resources = array();

    //items list
    protected $items = array();
    
    //child locations list
    //only temporary, later location class will be divided into
    //subclasses
    protected $buildings = array();
    protected $vehicles = array();

    function __construct($data) {
        parent::__construct($data);
        $this->slots = $data['slots'];
        $this->x = $data['x'];
        $this->y = $data['y'];
    }

    public function getFullResources() {
        $res_array = array();
        foreach ($this->resources as $res) {
            $resource = Model_Resource::getInstance($this->source)->findOneById($res, true);
            $res_array[] = $resource;
        }
        return $res_array;
    }
    
    public function toArray() {
        return array_merge(
            parent::toArray(), 
            array(
                'slots' => $this->slots,
                'x'=> $this->x,
                'y' => $this->y,
            )
        );
    }
    
    //musi być abstrakcyjna, żeby pobrać dane z odpowiedniego miejsca
    abstract function getRaws();
    abstract public function getBuildings();
    abstract function calculateUsedSlots();
 
}

?>
