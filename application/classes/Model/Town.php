<?php defined('SYSPATH') or die('No direct script access.');

class Model_Town extends ORM {  
    
    protected $_has_one = array(
        'location' => array(
            'model' => 'Location',
            'foreign_key' => 'location_id',
            'far_key' => 'id',
        )
    );
    
    public function getResources() {

    }
    
}

?>