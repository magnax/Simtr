<?php

class Model_Buildmenu extends ORM {
    
    public static function getMenu($menu_id) {
        
        return ORM::factory('buildmenu')->where('parent_id', '=', $menu_id)->find_all();
        
    }
    
}

?>
