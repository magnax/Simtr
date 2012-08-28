<?php defined('SYSPATH') or die('No direct script access.');

/**
 * user login controller
 */
class Controller_Login extends Controller_Base_Guest {

    //default login action - shows login form or validate/login user if POST
    public function action_index() {
        
        $this->view->bind('errors', $errors);
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            // Attempt to login user
            $remember = array_key_exists('remember', $this->request->post()) ? (bool) $this->request->post('remember') : FALSE;
            $user = Auth::instance()->login($this->request->post('email'), $this->request->post('password'), $remember);
             
            // If successful, redirect user
            if ($user) {
                
                Request::current()->redirect('user');
                
            } else {
                
                $errors['failed'] = 'Login failed, try again';
                print_r($this->view->errors);
                
                print_r($errors);
                
            }
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
        
        //send activation code
        $email = Email::factory()
            ->subject(__('Activate your Simtr account'))
            ->to($user->getEmail())
            ->bcc('magnax@gmail.com')
            ->from('noreply@example.com', 'Simtr');
        $email->message('Below is your activation code, click link or copy it and paste
            in browser address field.<br><br>
            <a href="'.URL::base().'index.php/login/activate?id='.
            $user->getID().'&code='.$activateCode.
            '">'.URL::base().'index.php/login/activate?id='.
            $user->getID().'&code='.$activateCode.'</a>', 'text/html');
        $email->send();
        

        $this->view->IDUser = $user->getID();

    }

    public function action_checklogin() {

        if (isset($_POST['iduser']) && $_POST['iduser'] && isset($_POST['pass']) && $_POST['pass']) {
            
            $user = Model_User::getInstance($this->redis);

            $authkey = $user->login($_POST['iduser'], $_POST['pass']);

            if ($authkey) {
                $this->session->set('authkey', $authkey);
                $this->request->redirect('user/menu');
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
            $this->redirectMessage($message, '/user/menu');
        } else {
            $this->redirectMessage($message, '/guest/login/loginform');
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
