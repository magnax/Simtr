<?php

abstract class Model_Resource {

    protected $id;
    protected $name;
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
    
    public function toArray() {
        return array(
            'id'=>$this->id,
            'name'=>$this->name,
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
