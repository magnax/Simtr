<?php defined('SYSPATH') or die('No direct script access.');

class Model_ItemType_Redis extends Model_ItemType {
    
    public function fetchOne($itemtype_id, $as_array = false) {
        $itemtype = json_decode($this->source->get("itemtype:$itemtype_id"), TRUE);
        if ($as_array) {
            return $itemtype;
        }
        $this->attack = $itemtype['attack'];
        
        return $this;
        
    }
    
    public function getName($item_id) {
        $itemtype = json_decode($this->source->get("itemtype:$item_id"), true);
        return $itemtype['name'];
    }
    
}

?>
