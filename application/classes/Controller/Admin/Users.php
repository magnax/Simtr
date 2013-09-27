<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Users extends Controller_Base_Admin {
    
    /**
     * shows all users
     */
    public function action_index() {
        
        $users = ORM::factory('User')->find_all();
        
        $this->template->content = View::factory('admin/users/index')
            ->bind('users', $users);
        
    }
    
    public function action_show() {
        
    }
    
    public function action_edit() {
        
    }
    
    public function action_delete() {
        
    }
    
}