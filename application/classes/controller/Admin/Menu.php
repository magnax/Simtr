<?php

class Controller_Admin_Menu extends Controller_Base_Admin {

    /**
     * główna strona panelu admina
     */
    public function action_index() {

        $this->template->content = new View('admin/menu/index');

    }

}

?>
