<?php

abstract class Model_Location {

    protected $PLACE_HEARABLE = array('loc', 'veh', 'shp');    

    protected $id;
    protected $x;
    protected $y;
    /**
     * Internal name
     * @var string 
     */
    protected $name;
    protected $res_slots;
    protected $used_slots;
    protected $resources = array();
    protected $projects = array();

    protected $source;

    public function  __construct($source) {
        $this->source = $source;
    }
    
    public static function getInstance($source) {
        if ($source instanceof Predis_Client) {
            return new Model_Location_Redis($source);
        }
    }
    
    public function getID() {
        return $this->id;
    }

    public function getX() {
        return $this->x;
    }
    
    public function getY() {
        return $this->y;
    }

    public function getName() {
        return $this->name;
    }

    public function getResSlots() {
        return $this->res_slots;
    }

    public function getUsedSlots() {
        return $this->used_slots;
    }

    public function getResources() {
        return $this->resources;
    }

    public function getFullResources() {
        $res_array = array();
        foreach ($this->resources as $res) {
            $resource = Model_Resource::getInstance($this->source)->findOneById($res, true);
            $res_array[] = $resource;
        }
        return $res_array;
    }


    public function update($post) {
        $this->x = $post['x'];
        $this->y = $post['y'];
        $this->name = $post['name'];
        $this->res_slots = $post['res_slots'];
    }


    public function toArray() {
        return array(
            'id'=>$this->id,
            'x'=>$this->x,
            'y'=>$this->y,
            'name'=>$this->name,
            'res_slots'=>$this->res_slots,
            'used_slots'=>$this->used_slots,
            'resources'=>$this->resources
        );
    }

    public function addProject($project_id, $save = false) {
        if (!in_array($project_id, $this->projects)) {
            $this->projects[] = $project_id;
            if ($save) {
                $this->saveProjects();
            }
        }
    }

    abstract public function findOneByID($location_id, $character_id);
    abstract public function getAllHearableCharacters();
    abstract public function calculateUsedSlots();
    abstract public function save();
    abstract public function saveProjects();
    abstract public function getExits($lnames);

}

?>