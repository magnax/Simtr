<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_ProjectTypes extends Controller_Base_Admin {
    
    /**
     * displays all project types list
     */
    public function action_index() {
        
        $this->view->projecttypes = ORM::factory('ProjectType')->find_all()->as_array();
        
    }

    /**
     * add or edit resource
     */
    public function action_edit() {
        
        if ($this->request->param('id')) {
            $projecttype = new Model_ProjectType($this->request->param('id'));
        } else {
            $projecttype = new Model_ProjectType();
        }
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            $projecttype->values($_POST);
            $projecttype->save();

            $this->redirect($this->request->post('redir'));
            
        }
        
        $this->view->projecttype = $projecttype;
    }
    
}

?>
