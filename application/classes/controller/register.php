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

                    // Grant user login role
                    $user->add('roles', ORM::factory('role', array('name' => 'login')));

                    // Reset values so form is not sticky
                    $_POST = array();

                    // Set success message
                    $this->session->set('msg', "You have registered with '{$user->email}' to the game");
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
    
}
?>
