<?php defined('SYSPATH') or die('No direct script access.');

class Model_LocationFactory {
    
    private static $valid_classes = array('town', 'building', 'vehicle');

    static function getInstance($location) {
        
//        $locationtype = $location->locationtype->find();
        
        return ORM::factory($location->locationtype->name)->where('location_id', '=', $location->id)->find();
        
    }

}

?>
