<?php

class Model_Redis_Item extends Model_Item {
    
    public function fetchAll($filter = null) {
        if ($filter) {
            $ids = $this->source->smembers("global:items:$filter");
        } else {
            $ids = $this->source->smembers("global:items");
        }
        if (count($ids)) {
            return array(1=>'bone_knife');
        } else {
            return null;
        }
    }
    
}

?>
