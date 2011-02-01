<?php

class Controller_Admin_Location extends Controller_Base_Admin {

    public function action_index() {

        $this->template->content = new View('admin/location/index');

    }

}

?>
