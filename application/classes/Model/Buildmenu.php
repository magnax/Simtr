<?php defined('SYSPATH') or die('No direct script access.');

class Model_Buildmenu extends ORM {
    
    public static function getMenu($menu_id) {
        
        return ORM::factory('BuildMenu')->where('parent_id', '=', $menu_id)->find_all();
        
    }
    
}

?>
