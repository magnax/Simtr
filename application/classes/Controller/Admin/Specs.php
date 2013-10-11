<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Specs extends Controller_Base_Admin {
    
    public function action_show() {
        
        $itemtype = new Model_ItemType($this->request->param('id'));
        
        if ($itemtype->loaded()) {
            
            if (HTTP_Request::POST == $this->request->method()) {
            
                $specs = new Model_Spec($this->request->post('id'));
                $specs->values($this->request->post());
                $specs->save();
                
            }
            
            $specs = ORM::factory('Spec')
                ->where('itemtype_id', '=', $itemtype->id)
                ->find();
            $raws = ORM::factory('Spec_Raw')
                ->where('itemtype_id', '=', $itemtype->id)
                ->find_all()
                ->as_array();
            $menus = ORM::factory('Buildmenu')
                ->find_all()
                ->as_array('id', 'name');
            $menus = array(0 => '- brak -') + $menus;
        }
        
        $mandatory_tools = $itemtype->get_mandatory_tools();
        $optional_tools = $itemtype->get_optional_tools();
        
        $this->view
            ->bind('itemtype', $itemtype)
            ->bind('specs', $specs)
            ->bind('raws', $raws)
            ->bind('menus', $menus)
            ->bind('mandatory_tools', $mandatory_tools)
            ->bind('optional_tools', $optional_tools)
            ->set('redir', Session::instance()->get('redir', $this->request->referrer()));
    }
    
    /**
     * adds new material specification to given item type
     */
    public function action_add() {
        
        $spec = new Model_Spec_Raw();
        
        $itemtype = new Model_ItemType($this->request->param('id'));
        
        if ($itemtype->loaded()) {
        
            if (HTTP_Request::POST == $this->request->method()) {
            
                $spec->values($this->request->post());
                $spec->save();
                
                $this->redirect($this->request->post('redir'));
                
            }
            
            $resources = ORM::factory('Resource')->order_by('name')->find_all()->as_array('id', 'name');
            $resources = array('0' => '-- wybierz --') + $resources;
            
            $this->template->content = View::factory('admin/specs/edit')
                ->bind('spec', $spec)
                ->bind('itemtype', $itemtype)
                ->bind('resources', $resources)
                ->set('redir', $this->request->referrer());
            
        }
        
    }
    
    public function action_delete() {
        
        $spec = new Model_Spec_Raw($this->request->param('id'));
        $spec->delete();
        $this->redirect(Session::instance()->get('redir', $this->request->referrer()));
        
    }
    
}

?>
