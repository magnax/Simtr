<?php defined('SYSPATH') or die('No direct script access.');

class Model_LocationType extends ORM {
    
    protected $_has_many = array(
        'locations' => array(
            'model' => 'location',
            'foreign_key' => 'locationtype_id',
            'far_key' => 'id'
        )
    );
    
}

?>
