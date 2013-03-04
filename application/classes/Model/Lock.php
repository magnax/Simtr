<?php defined('SYSPATH') or die('No direct script access.');

class Model_Lock extends ORM {
    
    protected $_belongs_to = array(
        'locktype' => array(
            'model' => 'Locktype',
            'foreign_key' => 'locktype_id',
            'far_key' => 'id'
        )
    );
    
}

?>