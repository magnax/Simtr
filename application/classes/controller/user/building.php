<?php

class Controller_User_Building extends Controller_Base_Character {

    public function action_index() {
        
        $buildings_ids = $this->location->getBuildings();
        
        //default empty buildings array
        $buildings = array();
        
        foreach ($buildings_ids as $building_id) {
            $building = ORM::factory('building')
                ->where('location_id', '=', $building_id)
                ->find();
            $buildings[] = $building;
        }
        
        $this->view->buildings = $buildings;
        
    }

}

?>
