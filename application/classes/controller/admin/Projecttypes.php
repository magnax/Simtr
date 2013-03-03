<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Projecttypes extends Controller_Base_Admin {
    
    /**
     * displays all project types list
     */
    public function action_index() {
        
        $this->view->projecttypes = ORM::factory('Projecttype')->find_all()->as_array();
        
    }

    /**
     * add or edit resource
     */
    public function action_edit() {
        
        if ($this->request->param('id')) {
            $projecttype = new Model_Projecttype($this->request->param('id'));
        } else {
            $projecttype = new Model_Projecttype();
        }
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            $projecttype->values($_POST);
            $projecttype->save();

            $this->redirect((isset($_POST['redir']) && $_POST['redir']) ? $_POST['redir'] : 'admin/projecttypes/');
            
        }
        
        if (isset($_GET['redir']) && $_GET['redir']) {
            $this->view->redir = $this->request->referrer();
        }
        $this->view->projecttype = $projecttype;
    }
    
}

?>
