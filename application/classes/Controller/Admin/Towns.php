<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Towns extends Controller_Base_Admin {
    
    
    public function action_index() {
        
        $this->view->towns = ORM::factory('Town')->find_all();
        
    }
    
    public function action_add() {
        
    }
    
    public function action_edit() {
        
        $this->view
            ->bind('errors', $errors);
        
        if (isset($_GET['id'])) {
            $town = ORM::factory('Town', $_GET['id']);
            $this->view->location = $town->location->as_array();
            $this->view->town = $town->as_array();
        } else {
            $this->view->location = array();
            $this->view->town = array();
        }
        
        if ($_POST) {
            
            if (isset($_POST['id'])) {
                $town = ORM::factory('Town', $_GET['id']);
                $location = $town->location;
            } else {
                $location = new Model_Location();
                $town = new Model_Town();
            }
            
            $location->values($_POST, array('name'));
            $location->save();
            
            $town->values($_POST, array('x', 'y', 'slots'));
            $town->location_id = $location->id;
            $town->save();
            
            Request::current()->redirect('admin/towns');
            
        }
        
    }
    
}
?>
