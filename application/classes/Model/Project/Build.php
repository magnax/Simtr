<?php defined('SYSPATH') or die('No direct script access.');

class Model_Project_Build extends Model_Project {

    protected $_object_name = 'project';

    public function __construct($id) {
        
        $this->_columns = array_merge($this->_columns, array('itemtype_id', 'building_name'));
        parent::__construct($id);
        
    }

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
    
    public function get_name(array $params = null) {
        
        $item = new Model_ItemType($this->itemtype_id);
        return 'Produkcja: '.$item->name;
        
    }


    public function name($project_data) {
        
        $item = new Model_ItemType($project_data['itemtype_id']);
        
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
                    
                    //dodać lokację (locationtype_id => 2 dla budynków, class_id => z tabeli locationclasses)
                    //
                    
                    //parametry budynku
        //$project['itemtype_id']
        $building_params = new Model_BuildingAttributes(array('locationclass_id' => $this->itemtype_id));
//                    $query = mysql_query("select * from buildings_attrs where locationclass_id={$project['itemtype_id']}") or die (mysql_error());
//                    $result = mysql_fetch_array($query);
        $capacity = $building_params->capacity_person;
        $max_weight = $building_params->max_weight;
        $building_name = $this->building_name;
        
        $new_location = new Model_Location();
        $new_location->locationtype_id = 2;
        $new_location->class_id = $this->itemtype_id;
        $new_location->parent_id = $location->id;
        $new_location->name = $building_name;
        $new_location->save();
        
        $building = new Model_Building();
        $building->location_id = $new_location->id;
        $building->capacity_person = $capacity;
        $building->max_weight = $max_weight;
        $building->save();
       
//                    $capacity = $result['capacity_person'];
//                    $max_weight = $result['max_weight'];
//                    $building_name = str_replace('Produkcja: ', '', $project['name']);
                    
//                    $query = mysql_query("insert into locations values (0, 2, {$project['itemtype_id']}, 
//                        {$project['place_id']}, '$building_name')") or die (mysql_error());
//                    $location_id = mysql_insert_id();
                    
//                    $sql = "insert into buildings values (0, $location_id, $capacity, $max_weight)";
//                    $query = mysql_query($sql) 
//                        or die ('3: ' . mysql_error(). ' SQL: ' . $sql);
     
        return 'BuildEnd';
        
    }
    
    public function add_event_params(Model_Event $event) {
        $event->add('params', array('name' => 'itemtype_id', 'value' => $this->itemtype_id));
        $event->add('params', array('name' => 'building_name', 'value' => $this->building_name));
        return $event;
    }
    
}

?>