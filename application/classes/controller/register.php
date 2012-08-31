<?php defined('SYSPATH') or die('No direct script access.');

/**
 * register controller
 */
class Controller_Register extends Controller_Base_Guest {
    
    /**
     * default action, shows register form, validates user, saves user
     * and redirects to main page
     */
    public function action_index() {
        
        $this->view->bind('errors', $errors);
        
        if ($_POST) {
            
            $post = Validation::factory($_POST)
                ->rule('rule_agreement', 'not_empty');
            
            if ($post->check()) {
                try {

                    // Create the user using form values
                    $user = ORM::factory('user')->create_user($this->request->post(), array(
                        'password',
                        'email'            
                    ));

                    $activateCode = Text::random('distinct', 16);
                    $user->activation_code =  $activateCode;
                    
                    $user->save();
                    
                    // Grant user login role
                    $user->add('roles', ORM::factory('role', array('name' => 'login')));

                    // Reset values so form is not sticky
                    $_POST = array();

                    // Set success message
                    $this->session->set('msg', "You have registered with '{$user->email}' to the game");

                    $message = 'Below is your activation code, click link or copy it and paste
                        in browser address field.<br><br>
                        <a href="'.Kohana::$config->load('general.site_url').'activate?id='.
                        $user->id.'&code='.$activateCode.
                        '">'.Kohana::$config->load('general.site_url').'index.php/login/activate?id='.
                        $user->id.'&code='.$activateCode.'</a>';
                                        
                    $email = Email::factory('Activate your Fabular account', $message)
                        ->message($message, 'text/html')
                        ->to($user->email)
                        ->bcc('magnax@gmail.com')
                        ->from('noreply@fabular.pl', 'Fabular.pl')
                        ->send();

                    Request::current()->redirect('login');

                } catch (ORM_Validation_Exception $e) {

                    // Set failure message
                    $message = 'There were errors, please see form below.';

                    // Set errors using custom messages
                    $errors = $e->errors('models');
                }
            } else {
                $errors = $post->errors('models');
               
            }
            
        }

    }
    
    public function action_email() {
        $email = Email::factory('Activate your Fabular account')
            ->message('To jest testowy email ze strony <a href="">jakiejś tam</a><h2>Testowy nagłów</h2>', 'text/html')
            ->to('magnax@gmail.com')
            ->bcc('mn@efemental.pl')
            ->from('noreply@fabular.pl', 'Fabular.pl')
            ->send();
    }
    
}
?>
