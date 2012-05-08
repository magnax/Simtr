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

        //creates (and saves) new user
        $user->createNew($_POST); 
        
        $activateCode = Text::random('distinct', 16);
        $user->setActivationCode($user->getID(), $activateCode);

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
    
    public function action_activate() {
        
        $id = $_GET['id'];
        $code = $_GET['code'];
       
        if (!$id || !is_numeric($id) || !$code || (strlen($code) != 16)) {
            $this->redirectError('Bad request!');
        }
        
        $user = Model_User::getInstance($this->redis);
        $activateCode = $user->fetchActivationCode($id);
        
        if (!$activateCode) {
            $user->getUserData($id);
            if ($user->isActive()) {
                $message = 'Account already activated';
            } else {
                //ups! user is not active and doesn't have activation code
                $this->redirectError('Something went wrong with activation process');
            }
        } elseif ($code == $activateCode) {           
            $user->activate($id);
            $message = 'Your account is now activated.';
        } else {
            $this->redirectError('Bad code!');
        }
        
        //not redirected means activation ok or user already active
        //check where to redirect
        if ($user->tryLogIn($this->session->get('authkey'))) {
            $this->redirectMessage($message, '/u/menu');
        } else {
            $this->redirectMessage($message, '/login');
        }
        
    }

    public function action_mailme() {
        //require Kohana::find_file('vendor/Swift-4.1.5', 'lib/swift_required');
        $email = Email::factory()
            ->subject(__('Message'))
            ->to('magnax@gmail.com')
            ->from('noreply@example.com', 'Example')
            ->reply_to('magnax@gmail.com');
        $email->message('Hello, I am test', 'text/html');
        $email->send();
    }
    
}

?>
