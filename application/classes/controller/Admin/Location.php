<?php

class Controller_Admin_Location extends Controller_Base_Admin {

    public function action_index($page = 1) {

        $locations = $this->redis->smembers("global:locations");
        
        $this->view->count = count($locations);
        
        $locations_array = array();
        
        foreach ($locations as $location) {
            $locations_array[] = Model_Location::getInstance($this->redis)->
                findOneByID($location, null)->
                toArray();
        }
        $this->view->locations = $locations_array;

    }

    public function action_edit($location_id) {
        
        $location = Model_Location::getInstance($this->redis)->findOneByID($location_id, 0);
        
        if (isset($_POST['submit'])) {
            $location->update($_POST);
            $location->save();
            //redirect to get rid of POST
            $this->request->redirect('admin/location/edit/'.$location_id);
        }
        
        $this->view->location = $location->toArray();
        $this->view->location['exits'] = $location->getExits();
        $this->view->location['resources'] = $location->getFullResources();       
        
    }
    
    public function action_add() {
        if (isset($_POST['submit'])) {
            $location = Model_Location::getInstance($this->redis);
            $location->update($_POST);
            $location->save();
            
            //redirect to get rid of POST
            $this->request->redirect('admin/location');
        }
    }
    
}

?>
