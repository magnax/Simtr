<?php

class Model_Project_Make extends Model_Project {

    protected $amount;

    /**
     * Identyfikator przedmiotu produkcji
     *
     * @var string
     */
    protected $item_id;
    protected $resource_id;

    public function toArray() {

        $tmp_arr = parent::toArray();
        $tmp_arr['name'] = 'Manufacturing '.$this->item_id;
        $tmp_arr['amount'] = $this->amount;
        $tmp_arr['resource_id'] = $this->resource_id;
        $tmp_arr['item_id'] = $this->item_id;

        return $tmp_arr;

    }

}

?>