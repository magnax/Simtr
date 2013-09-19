<?php defined('SYSPATH') or die('No direct script access.');

class Model_Project_Make extends Model_Project {

    protected $_object_name = 'project';

    public function __construct($id) {
        
        $this->_columns = array_merge($this->_columns, array('itemtype_id'));
        parent::__construct($id);
        
    }

    public function getProjectRequirements() {
        return null;
    }

    public function getUserRequirements($character_id) {
        return null;
    }
    
    public function get_name() {
        
        $item = new Model_ItemType($this->itemtype_id);
        return 'Produkcja: '.$item->name;
        
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
    
    public function settle(Model_Character $owner, Model_Location $location) {
        
        $itemtype = new Model_ItemType($this->itemtype_id);
        $item = new Model_Item;
        $item->itemtype_id = $itemtype->id;
        $item->points = $itemtype->points;
        $item->save();
        
        if ($owner->location_id == $this->location_id) {
            //owner is present
            $owner_possible_weight = $owner->calculate_free_weight();
            if ($owner_possible_weight >= $itemtype->weight) {
                //owner can get all
                $owner->addItem($item->id);
                return 'MakeEnd';
            }
        }

        $location->addItem($item->id);       
        return 'MakeEndGround';
        
    }
    
    public function add_event_params(Model_Event $event) {
        
        $event->add('params', array('name' => 'name', 'value' => $this->get_name()));
        return $event;
        
    }

}

?>