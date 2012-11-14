<?php defined('SYSPATH') or die('No direct script access.');

class Model_Project_Bury extends Model_Project {

    protected $body_id;
    protected $character_id;

    public function getProjectRequirements() {
        return null;
    }

    public function getUserRequirements($character_id) {
        return null;
    }

    public function toArray() {

        $tmp_arr = parent::toArray();
        $tmp_arr['body_id'] = $this->body_id;
        $tmp_arr['character_id'] = $this->character_id;

        return $tmp_arr;
        
    }

}

?>
