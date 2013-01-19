<?php defined('SYSPATH') or die('No direct script access.');

class Model_Spec_Raw extends ORM {
    
    public $_table_name = 'specs_raws';
    
    protected $_belongs_to = array(
        'itemtype' => array(
            'model' => 'itemtype',
            'foreign_key' => 'id',
            'far_key' => 'itemtype_id'
        ),
        'resource' => array(
            'model' => 'resource',
            'foreign_key' => 'resource_id',
            'far_key' => 'id'
        )
    );


    public static function getRaws($itemtype_id, $simple_table = false) {
        
        $specs = ORM::factory('Spec_Raw')
            ->with('resource')
            ->where('itemtype_id', '=', $itemtype_id)
            ->find_all();
        
        if ($simple_table) {
            return $specs->as_array('resource_id', 'amount');
        } else {
            return $specs->as_array();
        }
        
    }
    
}

?>
