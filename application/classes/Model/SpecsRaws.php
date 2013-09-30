<?php defined('SYSPATH') or die('No direct script access.');

class Model_SpecsRaws extends ORM {
    
    public $_table_name = 'specs_raws';
    
    protected $_belongs_to = array(
        'resource' => array(
            'model' => 'Resource',
            'foreign_key' => 'resource_id',
            'far_key' => 'id',
        ),
    );
    
}

?>
