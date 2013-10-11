<?php defined('SYSPATH') or die('No direct script access.');

class Model_ProjectType extends ORM {
    
    public static function get_all($select_list = true) {
        
        $returned = ORM::factory('ProjectType')->find_all();
        
        if ($select_list) {
            return $returned->as_array('id', 'name');
        }
        
        return $returned;
        
    }
    
}

?>
