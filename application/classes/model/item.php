<?php

class Model_Item extends ORM {
    
    protected $_has_one = array(
        'itemtype' => array(
            'model' => 'itemtype',
            'foreign_key' => 'id',
            'far_key' => 'itemtype_id'
        ),
    );


//    abstract public function fetchAll($filter);
    
}

?>
