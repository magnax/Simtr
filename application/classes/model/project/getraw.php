<?php

class Model_Project_GetRaw extends Model_Project {

    protected $amount;
    protected $resource_id;

    public function getProjectRequirements() {
        return null;
    }

    public function getUserRequirements($character_id) {
        return null;
    }

    public function toArray() {

        $tmp_arr = parent::toArray();
        $tmp_arr['amount'] = $this->amount;
        $tmp_arr['resource_id'] = $this->resource_id;

        return $tmp_arr;
        
    }

}

?>
