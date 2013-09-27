<?php defined('SYSPATH') or die('No direct script access.');

/**
 * user login controller
 */
class Controller_Sessions extends Controller_Base_Guest {

    //default login action - shows login form or validate/login user if POST
    public function action_login() {
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            // Attempt to login user
            $remember = array_key_exists('remember', $this->request->post()) ? (bool) $this->request->post('remember') : FALSE;
            $user = Auth::instance()->login($this->request->post('email'), $this->request->post('password'), $remember);
             
            // If successful, redirect user
            if (Auth::instance()->logged_in()) {
                
                $this->redirect('user');
                
            } else {
                
                $errors['failed'] = 'Login failed, try again';
                
            }
        }
        
        $this->template->content = View::factory('sessions/login')
            ->bind('errors', $errors);

    }

    public function action_logout() {
        
        // Log user out
        Auth::instance()->logout(TRUE);
         
        // Redirect to login page
        $this->redirect('login');
 
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
