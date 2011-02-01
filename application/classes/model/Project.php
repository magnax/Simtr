<?php

abstract class Model_Project {

    protected $id;
    protected $owner_id;
    protected $type_id;
    protected $place_type;
    protected $place_id;
    protected $amount;
    protected $time;
    protected $resource_id;
    protected $time_elapsed;
    protected $created_at;

    /**
     * zbiór uczestników projektu - do obliczania czasu
     * @var <type> 
     */
    protected $participants = array();
    
    /**
     * zbiór pracujących postaci (id)
     * @var <type> 
     */
    protected $workers = array();

    protected $source;

    protected $global_id_key = 'global:IDProject';

    public function  __construct($source) {
        $this->source = $source;
    }
    
    public static function getInstance($source) {

        if ($source instanceof Predis_Client) {
            return new Model_Project_Redis($source);
        }

    }

    public function getPercent($accuracy = 0) {
        return round(($this->time_elapsed / $this->time * 100), $accuracy);
    }

    public function getTypeId() {
        return $this->type_id;
    }

    public function toArray() {
        return array(
            'id'=>$this->id,
            'owner_id'=>$this->owner_id,
            'type_id'=>$this->type_id,
            'place_type'=>$this->place_type,
            'place_id'=>$this->place_id,
            'amount'=>$this->amount,
            'time'=>$this->time,
            'resource_id'=>$this->resource_id,
            'time_elapsed'=>$this->time_elapsed,
            'created_at'=>$this->created_at
        );
    }

    abstract public function set(array $data);
    abstract public function find($place_type, $place_id);
    abstract public function findOneById($id);
    abstract public function addParticipant(Model_Character $character, $time);
    abstract public function removeParticipant(Model_Character $character, $time);
    abstract public function getName();

}

?>
