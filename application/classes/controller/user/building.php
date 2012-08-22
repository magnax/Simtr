<?php

class Controller_User_Building extends Controller_Base_Character {

    public function action_index() {
        
        $buildings_ids = $this->location->getBuildings();
        
        //default empty buildings array
        $buildings = array();
        
        foreach ($buildings_ids as $building_id) {
            $building = Model_LocationFactory::getInstance($this->redis)
                ->fetchOne($building_id)
                ->toArray();
            $buildings[] = $building;
        }
        
        $this->view->buildings = $buildings;
        
    }

}

?>
