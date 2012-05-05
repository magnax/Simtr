<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * user login & register controller
 */
class Controller_Guest_Login extends Controller_Base_Guest {

    public function action_register() {
        
        if ($this->session->get('err')) {
            $this->view->err = $this->session->get_once('err');
        }
        
    }

    public function action_registerform() {

        //$session = Session::instance();
        if (!$this->session->get('continue') && ((Request::$method != 'POST') || !isset($_POST['confirm']) || ($_POST['confirm'] != 1))) {

            $this->session->set('err', 'You must check that you read all terms and conditions');
            Request::instance()->redirect('register');

        }

        if (!$this->session->get('continue')) {
            $this->session->set('continue', 1);
        }

    }

    /**
     * login form
     */
    public function action_loginform() {

        if ($this->session->get('err')) {
            $this->view->err = $this->session->get_once('err');
        }

    }

    public function action_checkuser() {

        if (!isset($_POST['email']) || !$_POST['email']) {
           $this->redirectError('You must provide valid email', 'registerform');
        }

        if (!isset($_POST['pass']) || !$_POST['pass']) {
           $this->redirectError('You must provide password', 'registerform');
        }

        $user = Model_User::getInstance($this->redis);

        if ($user->isDuplicateEmail($_POST['email'])) {
            $this->redirectError('This email already exists', 'registerform');
        }

        $user->createNew($_POST);

        $user->save();

        $this->view->IDUser = $user->getID();

    }

    public function action_checklogin() {

        if (isset($_POST['iduser']) && $_POST['iduser'] && isset($_POST['pass']) && $_POST['pass']) {
            
            $user = Model_User::getInstance($this->redis);

            $authkey = $user->login($_POST['iduser'], $_POST['pass']);

            if ($authkey) {
                $this->session->set('authkey', $authkey);
                $this->request->redirect('u/menu');
                return;
            } else {
                $this->redirectError('Incorrect ID or password', 'loginform');
            }
        } else {
            $this->redirectError('ID and password must be provided', 'loginform');
        }
        
        

    }

}

?>
