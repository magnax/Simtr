<?php defined('SYSPATH') or die('No direct script access.');

class Model_Project_Raw extends ORM {
    
    public $_table_name = 'projects_raws';
    
    protected $_belongs_to = array(
        'resource' => array(
            'model' => 'Resource',
            'foreign_key' => 'resource_id',
            'far_key' => 'id',
        ),
    );
    
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
