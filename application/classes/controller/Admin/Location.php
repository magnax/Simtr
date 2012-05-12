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
        
        $location = json_decode($this->redis->get("locations:$location_id"), true);
        
        if (isset($_POST['submit'])) {
            $location['x'] = $_POST['x'];
            $location['y'] = $_POST['y'];
            $location['name'] = $_POST['name'];
            $location['res_slots'] = $_POST['res_slots'];
            $this->redis->set("locations:$location_id", json_encode($location));
            //redirect to get rid of POST
            $this->request->redirect('admin/location/edit/'.$location_id);
        }
        
        $l = Model_Location::getInstance($this->redis)->findOneByID($location_id, 0);
        $location['exits'] = $l->getExits();
        
        foreach ($location['resources'] as $res) {
            $resource = Model_Resource::getInstance($this->redis)->findOneById($res, true);
            $location_res[] = $resource;
        }
        
        $location['resources'] = $location_res;
        $this->view->location = $location;
        
    }
    
}

?>
