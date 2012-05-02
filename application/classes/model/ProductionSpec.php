<?php

/**
 * specyfikacja produkcji (przedmiotu lub maszyny,
 * specyfikacje dla budynków, pojazdów i statków będą
 * rozszerzać tą klasę
 */
abstract class Model_ProductionSpec {

    protected $item;

    /**
     * tablica potrzebnych surowców: res_id => amount
     * @var <type> array
     */
    protected $raws = array();
    protected $items = array();
    protected $tools = array();
    protected $machines = array();
    protected $time;

    protected $source;
    protected $dict;

    protected function  __construct($source, $dict) {
        $this->source = $source;
        $this->dict = $dict;
    }

    public static function getInstance($source, $dict) {
        if ($source instanceof Predis_Client) {
            return new Model_ProductionSpec_Redis($source, $dict);
        }
    }

    public function toArray() {
        return array(
            'object'=>array(
                'name'=>$this->dict->getString($this->item),
                'item'=>$this->item,
            ),
            'time'=>$this->time,
            'raws'=>$this->raws,
            'items'=>$this->items,
            'tools'=>$this->tools,
            'machines'=>$this->machines
        );
    }

    abstract public function findOneById($id);
}

?>
