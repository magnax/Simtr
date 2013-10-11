<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Tools extends Controller_Base_Admin {
    
    public function action_add() {
        
        $tool = new Model_Tool();
        $tool->itemtype_id = $this->request->param('id');
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            $tool->values($this->request->post());
            $tool->save();
            
            $this->redirect('admin/specs/show/' . $tool->itemtype_id);
            
        }
        
        $itemtypes = ORM::factory('ItemType')->find_all()->as_array('id', 'name');
        $this->template->content = View::factory('admin/tools/edit')
            ->bind('tool', $tool)
            ->bind('itemtypes', $itemtypes);
        
    }
    
}

?>
