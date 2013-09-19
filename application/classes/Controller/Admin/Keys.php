<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Keys extends Controller_Base_Admin {
    
    public function action_delete() {
        
        RedisDB::del($this->request->param('id'));
        $this->redirect('admin/menu/keys');
        
    }
    
}

?>
