<?php

/**
 * rejestracja i logowanie użytkownika
 */
class Controller_Guest_Login extends Controller_Base_Guest {

    public function action_register() {

    }

    public function action_registerform() {

        //$session = Session::instance();
        if (!$this->session->get('continue') && ((Request::$method != 'POST') || !isset($_POST['confirm']) || ($_POST['confirm'] != 1))) {

            Request::instance()->redirect('register');

        }

        if (!$this->session->get('continue')) {
            $this->session->set('continue', 1);
        }

        //$this->view->err = ($this->session->get('err', null));

    }

    /**
     * formularz logowania
     */
    public function action_loginform() {

        $this->view->err = ($this->session->get('err', null));

    }

    /**
     * 1. Czy mail istnieje (global:emails)
     * 2. Utwórz nowe ID global:IDUser
     * 3. Dodaj do global:emails
     * 4. Utwórz users:$ID:password
     * 5. Utwórz users:$ID:email
     */
    public function action_checkuser() {

        if (!isset($_POST['email']) || !$_POST['email']) {
           $this->redirectError('Nie podano adresu e-mail', 'registerform');
        }

        if (!isset($_POST['pass']) || !$_POST['pass']) {
           $this->redirectError('Nie podano hasła', 'registerform');
        }

        $email = $_POST['email'];
        $password = $_POST['pass'];

        $email_exists = $this->redis->sismember('global:emails', $email);

        if ($email_exists) {
            $this->redirectError('Istnieje podany mail', 'registerform');
        }

        $IDUser = $this->redis->incr('global:IDUser');
        $this->redis->sadd('global:emails', $email);
        $this->redis->set("users:$IDUser:password", $password);
        $this->redis->set("users:$IDUser:email", $email);

        $this->redis->save();

        $this->view->IDUser = $IDUser;

    }

    public function action_checklogin() {

        if (isset($_POST['iduser']) && isset($_POST['pass'])) {
            $iduser = $_POST['iduser'];
            $password = $_POST['pass'];
        }

        $user = Model_User::getInstance($this->redis);

        $authkey = $user->login($iduser, $password);

        if ($authkey) {
            $this->session->set('authkey', $authkey);
            $this->request->redirect('u/menu');
        }

        $this->redirectError('Nieprawidłowy ID lub hasło', 'loginform');

    }

}

?>
