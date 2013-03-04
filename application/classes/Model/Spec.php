<?php

class Model_Spec extends ORM {
    
    protected $_belongs_to = array(
        'item' => array(
            'model' => 'ItemType',
            'foreign_key' => 'itemtype_id'
        )
    );
    
    public static function getItems($menu_id) {
        
        return ORM::factory('Spec')
            ->where('buildmenu_id', '=', $menu_id)
            ->find_all();
        
    }
    
}

?>