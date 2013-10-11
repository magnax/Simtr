<?php defined('SYSPATH') or die('No direct script access.');

class Model_Project_GetRaw extends Model_Project {

    protected $_object_name = 'project';

    public function __construct($id) {
        
        $this->_columns = array_merge($this->_columns, array('amount', 'resource_id'));
        parent::__construct($id);
        
    }

    public function getProjectRequirements() {
        return null;
    }

    public function getUserRequirements($character_id) {
        return null;
    }

    public function get_name(array $params = null) {
        
        $res = new Model_Resource($this->resource_id);
        
        return ucfirst($res->projecttype->name) . ' ' . $res->d;
        
    }
    
    /**
     * gets resources, items needed to starting this project
     * 
     * @return null as there's nothing needed to get raw resources
     */
    public function getSpecs($simple = false) {
        
        return null;
        
    }
    
    public function settle(Model_Character $owner, Model_Location $location) {
        
        if ($owner->location_id == $this->location_id) {
            //owner is present
            $owner_possible_weight = $owner->calculate_free_weight();
            if ($owner_possible_weight >= $this->amount) {
                //owner can get all
                $owner->addRaw($this->resource_id, $this->amount);
                return 'GetRawEnd';
            }
        }

        $location->addRaw($this->resource_id, $this->amount);       
        return 'GetRawEndGround';
        
    }
    
    public function add_event_params(Model_Event $event) {
        $event->add('params', array('name' => 'res_id', 'value' => $this->resource_id));
        $event->add('params', array('name' => 'amount', 'value' => $this->amount));
        return $event;
    }
    
}

?>
