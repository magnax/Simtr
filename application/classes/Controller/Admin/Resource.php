<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Resource extends Controller_Base_Admin {
    
    public function action_index() {
        
        $resources = ORM::factory('Resource')->find_all();
        
        $this->template->content = View::factory('admin/resource/index')
            ->bind('resources', $resources);
        
    }
    
    /**
     * add or edit resource
     */
    public function action_edit() {
        
        if ($this->request->param('id')) {
            $resource = new Model_Resource($this->request->param('id'));
        } else {
            $resource = new Model_Resource();
        }
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            $resource->values($_POST);
            $resource->save();

            $this->redirect($this->request->post('redir'));
            
        }
        
        $this->template->content = View::factory('admin/resource/edit')
            ->bind('resource', $resource)
            ->set('projecttypes', Model_ProjectType::get_all());
        
    }
    
}

?>