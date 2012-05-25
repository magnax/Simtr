<?php

class Controller_Admin_Resource extends Controller_Base_Admin {

    /**
     * displays all resources list
     */
    public function action_index() {
        $this->view->resources = Model_Resource::getInstance($this->redis)->
            findAll($name_only = false, $raw = false);
    }

    public function action_edit($res_id) {
        $resource = Model_Resource::getInstance($this->redis)->findOneById($res_id);
        
        if (isset($_POST['save'])) {
            $resource->update($_POST);
            
            $resource->save();
            //redirect to get rid of POST
            $this->request->redirect(isset($_POST['redir']) ? $_POST['redir'] : 'admin/resource/');
        }
        
        $this->view->resource = $resource->toArray();
    }
    
    /**
     * Adds resource to location
     * @param int $location_id 
     */
    public function action_add($location_id) {
        
        //fetch all raws
        $all_resources = Model_Resource::getInstance($this->redis)->
            findAll($name_only = true, $raw = true);
        //get location
        $location = Model_Location::getInstance($this->redis)->findOneByID($location_id, null);
        $location_resources = $location->getResources();
        
        $raw_resources = array();
        
        //now get only resources not binded to this location
        foreach ($all_resources as $k => $res) {
            if (!in_array($k, $location_resources)) {
                $raw_resources[$k] = $res;
            }
        }
        
        $this->view->resources = $raw_resources;
        
    }
    
    /**
     * adds new resource at global level 
     */
    public function action_new() {
        
        $resource = Model_Resource::getInstance($this->redis);
        
        if (isset($_POST['save'])) {
            $resource->update($_POST);
            
            $resource->save();
            //redirect to get rid of POST
            $this->request->redirect(isset($_POST['redir']) ? $_POST['redir'] : 'admin/resource/');
        }
        
    }
}

?>
