<?php

abstract class Model_Resource {

    protected $id;
    protected $name;
    protected $type;
    /**
     * podstawowa ilość zbierana na dzień
     * @var integer
     */
    protected $gather_base;

    protected $source;

    public function  __construct($source) {
        $this->source = $source;
    }

    public function getGatherBase() {
        return $this->gather_base;
    }

    public function getType() {
        return $this->type;
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
    
    public function setType($type) {
        $this->type = $type;
    }
    
    public function setGatherBase($base) {
        $this->gather_base = $base;
    }
    
    public function toArray() {
        return array(
            'id'=>$this->id,
            'name'=>$this->name,
            'type'=>$this->type,
            'gather_base'=>$this->gather_base
        );
    }

    public static function getInstance($source) {
        if ($source instanceof Predis_Client) {
            return new Model_Resource_Redis($source);
        }
    }

    abstract public function findOneById($id);

}

?>
