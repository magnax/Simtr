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
    
    public function name($project_data, $character_id, $lang = 'pl') {
        
        $buried_char = new Model_Character($project_data['character_id']);
        $buried_name = ORM::factory('chname')->name($character_id, $buried_char)->name;
        if (!$buried_name) {
            $buried_name = ORM::factory('character')->getUnknownName($buried_char, $lang);
        }
        return 'Zakopywanie ciała '.'<a href="/chname?id='.$buried_char.'">'.$buried_name.'</a>';
    }

}

?>
