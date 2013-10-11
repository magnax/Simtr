<?php defined('SYSPATH') or die('No direct script access.');

class Model_Project_Road extends Model_Project {

    public function __construct($id) {
        
        $this->_columns = array_merge($this->_columns, array('itemtype_id', 'road_id'));
        parent::__construct($id);
        
    }

    public function getProjectRequirements() {
        return null;
    }

    public function getUserRequirements($character_id) {
        return null;
    }
    
    public function get_name(array $params = null) {

        $road = new Model_Road($this->road_id);
        
        $road_type = Model_RoadType::get_next_level($road->level);
        
        if ($params['location']->id == $road->location_1_id) {
            $dest_location = $road->location_2;
            $reverse = false;
        } else {
            $dest_location = $road->location_1;
            $reverse = true;
        }
        
        $dest_name = HTML::anchor('lname/'.$dest_location->id, $dest_location->get_lname($params['character']->id));

        $direction = $road->get_direction_string($reverse);
        $level = $road_type->name;
        
        return sprintf('Ulepszanie drogi do %s (kierunek %s) do poziomu: %s', 
            $dest_name, $direction, $level);
        
    }
    
    public function getAllSpecs() {
        
        $raws = Model_Project_Raw::getRaws($this->id);
        return $raws;
        foreach ($raws as $raw) {

            if ($raw->amount < $raw->needed) {
                $added = $raw->amount;
            } else {
                $added = 0;
            }

            $all_specs[] = array(
                'resource_id' => $raw->resource_id,
                'name' => $raw->resource->name,
                'needed' => $raw->needed,
                'added' => $added
            );

        }

        return $all_specs;
        
    }
    
    /**
     * this method would be overriden in child classes
     * 
     * @return boolean
     */
    public function hasAllResources() {
        
        return false;
        
    }
    
    public function hasAllSpecs() {
        
        $raws = Model_Project_Raw::getRaws($this->id);

        foreach ($raws as $raw) {

            if ($raw->amount < $raw->needed) {
                return false;
            }
            
        }

        return true;
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
    
    public function get_mandatory_tools() {
        
        $itemtype = new Model_ItemType($this->itemtype_id);
        
        return $itemtype->get_mandatory_tools();
        
    }
    
    public function get_optional_tools() {
        
        $itemtype = new Model_ItemType($this->itemtype_id);
        
        return $itemtype->get_optional_tools();
        
    }

}

?>
