<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_ItemTypes extends Controller_Base_Admin {
    
    public function action_index() {
        
        $itemtypes = ORM::factory('ItemType')->find_all();
        
        $this->template->content = View::factory('admin/itemtypes/index')
            ->bind('itemtypes', $itemtypes);
        
    }
    
    /**
     * add or edit resource
     */
    public function action_edit() {
        
        if ($this->request->param('id')) {
            $itemtype = new Model_ItemType($this->request->param('id'));
        } else {
            $itemtype = new Model_ItemType();
        }
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            $itemtype->values($_POST);
            $itemtype->save();

            $this->redirect($this->request->post('redir'));
            
        }
          
        $projecctypes = array('0' => '-- wybierz --') +
                ORM::factory('ProjectType')->find_all()->as_array('id', 'name');
        $this->view->itemtype = $itemtype;
        $this->view->projecctypes = $projecctypes;
    }
    
}

?>
