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
        
        if (HTTP_Request::POST == $this->request->method()) {
            $pattern = $this->request->post('pattern');
            Session::instance()->set('pattern', $pattern);
        } else {
            $pattern = Session::instance()->get('pattern', '*');
        }
        
        $keys = $this->redis->keys($pattern);
        asort($keys);
        
        $this->view->keys = $keys;
        $this->view->pattern = $pattern;
    }

}

?>
