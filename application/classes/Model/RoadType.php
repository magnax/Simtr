<?php defined('SYSPATH') or die('No direct script access.');

class Model_RoadType extends ORM {
    
    public function get_raws($road_distance) {
        
        $raws = ORM::factory('SpecsRaws')
            ->where('itemtype_id', '=', $this->itemtype_id)
            ->find_all()
            ->as_array();
        
        foreach ($raws as &$raw) {
            $raw->amount = ceil($raw->amount * $road_distance);
        }
        
        return $raws;
        
    }
    
    public static function get_next_level($level) {
        
        return ORM::factory('RoadType')
            ->where('level', '=', $level + 1)
            ->find();
        
    }
    
}

?>
