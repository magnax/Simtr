<?php

class Controller_Admin_Login extends Controller_Base_Admin {

    public function action_index() {

        $this->template->content = new View('admin/login/index');

    }

}

?>
