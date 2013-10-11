<?php defined('SYSPATH') or die('No direct script access.');

class Model_Tool extends ORM {
    
    protected $_belongs_to = array(
        'itemtype' => array(
            'model' => 'ItemType',
            'foreign_key' => 'itemtype_id',
            'far_key' => 'id',
        ),
        'required_itemtype' => array(
            'model' => 'ItemType',
            'foreign_key' => 'req_itemtype_id',
            'far_key' => 'id',
        ),
    );
    
}

?>
