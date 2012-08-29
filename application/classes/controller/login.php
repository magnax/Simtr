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
                
            }
        }

    }

    public function action_logout() {
        
        // Log user out
        Auth::instance()->logout();
         
        // Redirect to login page
        $this->request->redirect('login');
 
    }
    
    public function action_activate() {
       
        if (!isset($_GET['id']) 
            || !is_numeric($_GET['id']) 
            || !isset($_GET['code']) 
            || (strlen($_GET['code']) != 16)) {
            
            $this->redirectError('Bad request!');
        }
        
        $user = ORM::factory('user', $_GET['id']);
        
        if (!$user->id) {
            $this->redirectError('This user does not exists!');
        }
        
        if ($user->active) {
            $this->session->set('msg', 'User already active');
            $this->request->redirect('login');
        }
        
        $activateCode = $user->activation_code;
        
        if (!$activateCode) {
            $this->redirectError('Something went wrong with activation process');
        }
        
        if ($_GET['code'] == $activateCode) {           
            
            $user->active = 1;
            $user->activation_code = null;
            $user->save();
            $this->session->set('msg', 'Your account is now activated.');
            $this->request->redirect('login');
            
        } else {
            
            $this->redirectError('Bad activation code!');
            
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
