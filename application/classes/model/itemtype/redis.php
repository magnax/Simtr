<?php

class Model_ItemType_Redis extends Model_ItemType {
    
    public function getName($item_id) {
        $itemtype = json_decode($this->source->get("itemtype:$item_id"), true);
        return $itemtype['name'];
    }
    
}

?>
