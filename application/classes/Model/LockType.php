<?php defined('SYSPATH') or die('No direct script access.');

class Model_Locktype extends ORM {
    
    protected $_has_many = array(
        'locks' => array(
            'model' => 'Lock',
            'foreign_key' => 'locktype_id',
            'far_id' => 'id'
        )
    );
    
    public static function getUpgradeLevels($current_level) {
        
        return ORM::factory('LockType')
            ->where('level', '>', $current_level)
            ->find_all()
            ->as_array();
        
    }
    
}

?>
