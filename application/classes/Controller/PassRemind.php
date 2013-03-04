<?php defined('SYSPATH') or die('No direct script access.');

/**
 * password remind controller
 */
class Controller_PassRemind extends Controller_Base_Guest {
    
    function action_index() {
        
        if ($_POST) {
            $user = ORM::factory('user')->where('email', '=', trim($_POST['email']))->find();
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
