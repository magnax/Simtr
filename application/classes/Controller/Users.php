<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller for management actions: register, password remainder, activate
 */
class Controller_Users extends Controller_Base_Guest {
    
    /**
     * register user action, shows register form, validates user, saves user
     * and redirects to main page
     */
    public function action_register() {
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            try {

                $user = new Model_User;
                $user->values($this->request->post());
                $user->activation_code =  Text::random('distinct', 16);

                $user->create(Model_User::registration_validation($this->request->post()));

                // Grant user login role
                $user->add('roles', ORM::factory('Role', array('name' => 'login')));

                // Set success message
                Session::instance()->set('message', "Nowe konto dla adresu '{$user->email}' zostało utworzone.");

                $message = 'Below is your activation code, click link or copy it and paste
                    in browser address field.<br><br>
                    <a href="'.Kohana::$config->load('general.site_url').'activate?id='.
                    $user->id.'&code='.$user->activation_code.
                    '">'.Kohana::$config->load('general.site_url').'index.php/login/activate?id='.
                    $user->id.'&code='.$user->activation_code.'</a>';

                $email = Email::factory('Activate your Fabular account')
                    ->message($message, 'text/html')
                    ->to($user->email)
                    ->bcc('magnax@gmail.com')
                    ->from('noreply@fabular.pl', 'Fabular.pl')
                    ->send();

                $this->redirect('login');

            } catch (ORM_Validation_Exception $e) {
                // Set errors using custom messages
                $errors = $e->errors('models');
            }
            
        }
        
        $this->template->content = View::factory('users/register')
            ->bind('errors', $errors);

    }
    
    public function action_activate() {
       
        if (!isset($_GET['id']) 
            || !is_numeric($_GET['id']) 
            || !isset($_GET['code']) 
            || (strlen($_GET['code']) != 16)) {
            
            $this->redirectError('Bad request!');
        }
        
        $user = ORM::factory('User', $_GET['id']);
        
        if (!$user->id) {
            $this->redirectError('This user does not exists!');
        }
        
        if ($user->active) {
            $this->redirectMessage('User already active', 'user');
        }
        
        $activateCode = $user->activation_code;
        
        if (!$activateCode) {
            $this->redirectError('Something went wrong with activation process');
        }
        
        if ($_GET['code'] == $activateCode) {           
            
            $user->active = 1;
            $user->activation_code = null;
            $user->save();
            $this->redirectMessage('Your account is now activated.', 'user');
            
        } else {
            
            $this->redirectError('Bad activation code!');
            
        }
        
    }
    
    /**
     * Forgotten password - sends new password to email
     * @todo instead of saving new password, set it in database and activate 
     *      after confirm by user
     */
    function action_remind() {
        
        if ($_POST) {
            $user = ORM::factory('User')->where('email', '=', trim($_POST['email']))->find();
            if ($user->id) {
                $new_password = Text::random('distinct', 8);
                $user->password = $new_password;
                
                $user->save();
                
                $message = 'Your new password: '.$new_password;

                $email = Email::factory('New password for your Fabular account')
                    ->message($message, 'text/html')
                    ->to($user->email)
                    ->bcc('magnax@gmail.com')
                    ->from('noreply@fabular.pl', 'Fabular.pl')
                    ->send();

                Session::instance()->set('msg', 'New password sended, read email and log in');
                
                $this->redirect('login');
            } else {
                $this->template->error = 'Cannot find user registered with this email address';
            }
        }
        
    }
    
}

?>
