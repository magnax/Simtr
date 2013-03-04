<?php defined('SYSPATH') or die('No direct script access.');

class Model_Town extends ORM {  
    
    protected $_belongs_to = array(
        'location' => array(
            'model' => 'location',
            'foreign_key' => 'location_id',
            'far_key' => 'id',
        )
    );
    
    public function getResources() {

    }
    
}

?>