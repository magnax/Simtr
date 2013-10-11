<?php defined('SYSPATH') or die('No direct script access.');

class Model_Machine extends ORM {
    
    protected $_belongs_to = array(
        'itemtype' => array(
            'model' => 'ItemType',
        ),
    );
    
}

?>
