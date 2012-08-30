<?php defined('SYSPATH') or die('No direct script access.');

class Model_LName extends ORM {

    public function name($char_id, $location_id) {
        
        return $this->where('char_id', '=', $char_id)
            ->and_where('location_id', '=', $location_id)
            ->find();
        
    }

}

?>
