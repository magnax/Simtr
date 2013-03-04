<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Location extends Controller_Base_Admin {

    protected $locations_classes = array(
        '' => '---',
        'twn' => 'town',
        'roa' => 'road',
        'bld' => 'building',
        'veh' => 'vehicle',
    );
    
    protected $level_types = array(
        'path'=>'ścieżka',
        'sand'=>'piaszczysta',
        'paved'=>'brukowana',
        'highway'=>'szosa',
        'express'=>'autostrada',
    );


    public function action_index($page = 1) {
        
        $this->view->locationsCount = ORM::factory('Location')->count_all();

    }

    public function action_all() {
        
        $locations = $this->redis->smembers("global:locations");
        
        $this->view->count = count($locations);
        
        $locations_array = array();
        
        foreach ($locations as $location_id) {
             $locations_array[] = json_decode($this->redis->get("locations:$location_id"), true);
        }
        $this->view->locations = $locations_array;
    }

    public function action_edit($location_id) {

        //save changed location data
        if (isset($_POST['submit'])) {   
            $this->_add($_POST, $location_id);
        }
        
        //append building
        if (isset($_POST['append'])) {   
            
            $building = json_decode($this->redis->get("locations:{$_POST['building_id']}"), true);
            $building['parent'] = $location_id;
            $this->redis->set("locations:{$_POST['building_id']}", json_encode($building));
            $this->redis->sadd("twn:$location_id:bld", $_POST['building_id']);
            
            $this->redirect('admin/location/edit/'.$location_id);
            
        }
        
        //get location
        $location = json_decode($this->redis->get("locations:$location_id"), true);
        
        $location['resources'] = array(); 
        
        //buildings
        $bld_ids = $this->redis->smembers("twn:$location_id:bld");
        
        $locations_array = array();
        
        foreach ($bld_ids as $bld_id) {
            $locations_array[] = json_decode($this->redis->get("locations:$bld_id"), true);
        }
        $location['buildings'] = $locations_array;
        
        $this->view->location = $location;
        
        $types = json_decode($this->redis->get("loc_type"), true);
        $this->view->types = $types;
        
        $this->view->locations_classes = $this->locations_classes;
        $this->view->level_types = $this->level_types;
        
        //buildings not added to any location
        $bld_ids = $this->redis->smembers("bld");
        
        $locations_array = array();
        
        foreach ($bld_ids as $location_id) {
            $building = json_decode($this->redis->get("locations:$location_id"), true);
            if (!isset($building['parent']) or !$building['parent']) {
                $locations_array[$building['id']] = $building['name'].' ('.$building['type'].')';
            }
        }
        $this->view->orphan_buildings = $locations_array;
        
    }
    
    public function action_add() {
        
        if (isset($_POST['submit'])) {    
            $this->_add($_POST);
        }
        
        $this->view->location = array();
        $this->view->locations_classes = $this->locations_classes;
        $this->view->level_types = $this->level_types;

        $types = json_decode($this->redis->get("loc_type"), true);
        $this->view->types = $types;
        
    }
    
    private function _add($post, $location_id = null) {
        
        if (!$location_id) {
            $location_id = $this->redis->incr("global:IDLocation");
        }
        
        $location_data = array(
            'id' => $location_id,
            'name' => $post['name'],
            'type' => $post['type'],
            'class' => $post['class'],
            'parent' => $post['parent'] ? $post['parent'] : null,
        );
        
        switch ($post['class']) {
                
            case 'twn':
                $location_data = array_merge(
                    $location_data, 
                    array(
                        'x' => $post['x'],
                        'y' => $post['y'],
                        'slots' => $post['slots']
                    )
                );
                $key = "twn";
                break;
            case 'bld':
                $location_data = array_merge(
                    $location_data, 
                    array(
                        'capacity' => $post['capacity'],
                    )
                );
                $key = "bld";
                break;

        }

        $this->redis->set("locations:$location_id", json_encode($location_data));
        $this->redis->sadd($key, $location_id);
        //redirect to get rid of POST
        $this->redirect('admin/location');

    }
    
    //un-attach building from location
    public function action_removebuilding($location_id, $building_id) {
        
        $building = json_decode($this->redis->get("locations:$building_id"), true);
        $building['parent'] = null;
        $this->redis->set("locations:$building_id", json_encode($building));
        $this->redis->srem("twn:$location_id:bld", $building_id);
        
        $this->redirect('admin/location/edit/'.$location_id);
        
    }
    
}

?>
