<?php

class Controller_User_Profil extends Controller_Base_User {

    public function action_index() {
        $this->view->user_data = $this->user->toArray();
    }

    public function action_save() {
        $this->user->update($_POST);
        $this->request->redirect('u/menu');
    }

}

?>
