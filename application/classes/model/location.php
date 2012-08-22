<?php defined('SYSPATH') or die('No direct script access.');

abstract class Model_Location {  

    //attributes which every location has:
    protected $id;
    
    /**
     * Internal name
     * @var string 
     */
    protected $name;
    
    /**
     * type of location (ex. grassland or mountains for towns, tandem or van 
     * for vehicles, hut or stone_extension for buildings and so on
     * @var string 
     */
    protected $type;
    
    /**
     * class of location: loc, bld, shp, veh etc.
     * @var type 
     */
    protected $class;
    
    /**
     * parent location, or null if town location
     * @var int
     */
    protected $parent = null;

    /**
     * Data source (redis, mysql, whatever...)
     * @var object
     */
    protected $source = null;

    public function  __construct($data) {
        
        if ($data) {
            $this->id = isset($data['id']) ? $data['id'] : null;
            $this->class = isset($data['class']) ? $data['class'] : 'loc';
            $this->type = isset($data['type']) ? $data['type'] : null;
            $this->name = $data['name'];
            $this->parent = isset($data['parent']) ? $data['parent'] : null;
        }
        
    }
    
    //set data source
    public function setSource($source) {
        $this->source = $source;
    }

    public function getID() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }
    
    //returns location class
    public function getClass() {
        return $this->class;
    }

    //generic update
    public function update($post) {
        $this->name = $post['name'];
        $this->type = $post['type'];
        $this->class = $post['class'];
    }


    public function toArray() {
        return array(
            'id'=>$this->id,
            'name'=>$this->name,
            'type'=>$this->type,
            'class'=>$this->class,
            'parent'=>$this->parent,
        );
    }

    public function getChildLocations() {
        return null;
    }

        //get list of all characters (in this location, parent lokation, child 
    //locations or even another location) who can hear events (ie. talk)
    abstract public function getAllHearableCharacters($as_array = false);
    
    //get list of all characters (in this location, parent lokation, child 
    //locations or even another location) who can view events (ie. get/put items)
    abstract public function getAllVisibleCharacters($as_array = false);
    
    //saves location in database
    abstract public function save();
    

}

?>
