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
        
        $locations = ORM::factory('Location')->find_all();
        
        $this->template->content = View::factory('admin/location/index')
            ->set('locations_count', ORM::factory('Location')->count_all())
            ->bind('locations', $locations);

    }

    public function action_show() {
        
        
        
    }

    public function action_edit() {

        $location = new Model_Location($this->request->param('id'));
        
        if ($location->loaded()) {
            $location_detail_object = $location->get_detail_object();
        }
        
        $possible_parent_locations = Model_Location::get_possible_parent_locations();
        $towns = Model_Location::get_towns();
        
        $location_classes = array(0 => '-- wybierz --') + 
            ORM::factory('LocationClass')
                ->find_all()
                ->as_array('id', 'name');
        
        $location_types = array(0 => '-- wybierz --') + 
            ORM::factory('LocationType')
                ->find_all()
                ->as_array('id', 'name');
        
        if ($location->is_town()) {
            $resources = $location->resources->find_all();
        }
        
        if ($location->is_workable()) {
            $machines = $location->machines->find_all();
        }
        
        $this->template->content = View::factory('admin/location/edit')
            ->bind('location', $location)
            ->bind('possible_parent_locations', $possible_parent_locations)
            ->bind('location_classes', $location_classes)
            ->bind('location_types', $location_types)
            ->bind('location_detail_object', $location_detail_object)
            ->bind('machines', $machines)
            ->bind('resources', $resources)
            ->bind('towns', $towns);
        
        
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
