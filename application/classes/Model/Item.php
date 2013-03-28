<?php

class Model_Item extends ORM {
    
    protected $_belongs_to = array(
        'itemtype' => array(
            'model' => 'ItemType',
        ),
    );

    public function points_percent() {
        
        return round(100 * $this->points / $this->itemtype->points, 0);
        
    }
//    abstract public function fetchAll($filter);
    
}

?>
