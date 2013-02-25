<?php defined('SYSPATH') or die('No direct script access.');

class Model_Locktype extends ORM {
    
    public static function getUpgradeLevels($current_level) {
        
        return ORM::factory('locktype')
            ->where('level', '>', $current_level)
            ->find_all()
            ->as_array();
        
    }
    
}

?>
