<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Machines extends Controller_Base_Admin {
    
    public function action_add() {
        
        $machine = new Model_Machine();
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            $machine->itemtype_id = $this->request->post('itemtype_id');
            $machine->location_id = $this->request->param('id');
            
            $machine->save();
            
            $this->redirect('admin/location/edit/' . $machine->location_id);
            
        }
        
        $machine_types = ORM::factory('ItemType')->find_all()->as_array('id', 'name');
        
        $this->template->content = View::factory('admin/machines/edit')
            ->bind('machine', $machine)
            ->bind('machine_types', $machine_types);
        
    }
    
}

?>
