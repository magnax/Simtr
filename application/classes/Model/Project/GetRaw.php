<?php defined('SYSPATH') or die('No direct script access.');

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

    public function getName() {
        $res = new Model_Resource($this->resource_id);
        return 'Kopanie lub zbieranie '.$res->d;
    }
    
    public function name($project_data) {
        $res = new Model_Resource($project_data['resource_id']);
        return 'Kopanie lub zbieranie '.$res->d;
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