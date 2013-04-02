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

    public function get_name() {
        $res = new Model_Resource($this->resource_id);
        return 'Kopanie lub zbieranie ' . $res->d;
    }
    
    /**
     * gets resources, items needed to starting this project
     * 
     * @return null as there's nothing needed to get raw resources
     */
    public function getSpecs($simple = false) {
        
        return null;
        
    }
    
}

?>
