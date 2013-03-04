<?php

class Controller_Admin_Menu extends Controller_Base_Admin {

    /**
     * główna strona panelu admina
     */
    public function action_index() {

        $this->template->content = new View('admin/menu/index');

    }
    
    //dump all keys in database
    public function action_keys() {
        $keys = $this->redis->keys("*");
        asort($keys);
        $this->view->keys = $keys;
    }

}

?>
