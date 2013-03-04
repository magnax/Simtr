<?php defined('SYSPATH') or die('No direct script access.');

class Model_Building extends ORM {
    
    protected $_belongs_to = array(
        'location' => array(
            'model' => 'Location',
            'foreign_key' => 'location_id',
            'far_key' => 'id'
        ),
    );
    
}

?>
