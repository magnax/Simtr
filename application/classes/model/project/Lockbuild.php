<?php defined('SYSPATH') or die('No direct script access.');

class Model_Project_Lockbuild extends Model_Project {

    public $amount;
    public $itemtype_id;
    public $name;

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
    
    public function getName() {
        
        $item = new Model_ItemType($this->itemtype_id);
        return 'Wstawianie zamka: '.$item->name;
        
    }


    public function name($project_data) {
        
        $item = new Model_ItemType($project_data['itemtype_id']);
        
        return 'Wstawianie zamka: '.$item->name;
        
    }
    
     /**
     * gets resources, items needed to starting this project
     * 
     * @return Array
     */
    public function getSpecs($simple = false) {
        
        return Model_Spec_Raw::getRaws($this->itemtype_id, $simple);
        
    }
    
    /**
     * this method would be overriden in child classes
     * 
     * @return boolean
     */
    public function hasAllResources() {
        
        return false;
        
    }

}

?>