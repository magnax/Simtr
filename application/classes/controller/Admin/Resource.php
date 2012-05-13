<?php

class Controller_Admin_Resource extends Controller_Base_Admin {

    public function action_edit($res_id) {
        $resource = Model_Resource::getInstance($this->redis)->findOneById($res_id);
        
        if (isset($_POST['save'])) {
            $resource->setName($_POST['name']);
            $resource->setType($_POST['type']);
            $resource->setGatherBase($_POST['gather_base']);
            
            $resource->save();
            $this->request->redirect('admin/resource/edit/'.$res_id);
        }
        
        $this->view->resource = $resource->toArray();
    }
    
    /**
     * Adds resource to location
     * @param int $location_id 
     */
    public function action_add($location_id) {
        
    }
    
}

?>
