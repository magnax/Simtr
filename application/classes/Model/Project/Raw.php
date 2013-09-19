<?php defined('SYSPATH') or die('No direct script access.');

class Model_Project_Raw extends ORM {
    
    public $_table_name = 'projects_raws';
    
    public static function getRaws($project_id, $simple_table = false) {
        
        $raws = ORM::factory('Project_Raw')
            ->where('project_id', '=', $project_id)
            ->find_all();

        if ($simple_table) {
            return $raws->as_array('resource_id', 'amount');
        } else {
            return $raws->as_array();
        }
        
    }
    
}

?>
