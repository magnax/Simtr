<?php defined('SYSPATH') or die('No direct script access.');

class Model_ChName extends ORM {
    
    public function name($current_id, $lookup_id) {
        
        return $this->where('char_id', '=', $current_id)
            ->and_where('lookup_id', '=', $lookup_id)
            ->find();
        
    }
    
}

?>
