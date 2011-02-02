<?php

abstract class Model_Location {

    protected $PLACE_HEARABLE = array('loc', 'veh', 'shp');
    
    protected $id;
    protected $name;
    protected $res_slots;
    protected $used_slots;
    protected $resources = array();

    protected $source;

    public function  __construct($source) {
        $this->source = $source;
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

    public function toArray() {
        return array(
            'id'=>$this->id,
            'name'=>$this->name,
            'res_slots'=>$this->res_slots,
            'used_slots'=>$this->used_slots,
            'resources'=>$this->resources
        );
    }

    public static function getInstance($source) {
        if ($source instanceof Predis_Client) {
            return new Model_Location_Redis($source);
        }
    }

    abstract public function findOneByID($location_id, $character_id);
    abstract public function getAllHearableCharacters();
    abstract public function calculateUsedSlots();
    abstract public function save();

}

?>
