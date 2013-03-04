<?php

class Model_Item extends ORM {
    
    protected $_belongs_to = array(
        'itemtype' => array(
            'model' => 'ItemType',
        ),
    );


//    abstract public function fetchAll($filter);
    
}

?>
