<?php

class Controller_Guest_Error extends Controller_Template {

    public $template = 'templates/error';

    public function action_index() {

        $session = Session::instance();

        $this->template->errmsg = $session->get('err', 'Nieznany błąd');
        $session->delete('err');

    }

}

?>
