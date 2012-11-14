<?php defined('SYSPATH') or die('No direct script access.');

class Model_Project_Make extends Model_Project {

    protected $amount;
    protected $itemtype_id;

    /**
     * Identyfikator przedmiotu produkcji
     *
     * @var string
     */
    protected $item_id;
    protected $resource_id;

    public function getProjectRequirements() {
        return null;
    }
    
    public function getUserRequirements($character_id) {
        return null;
    }
    
    public function toArray() {

        $tmp_arr = parent::toArray();
        $tmp_arr['name'] = $this->name;
        $tmp_arr['amount'] = $this->amount;
        $tmp_arr['itemtype_id'] = $this->itemtype_id;

        return $tmp_arr;

    }

}

?>