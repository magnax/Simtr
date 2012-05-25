<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * password remind controller
 */
class Controller_Guest_PassRemind extends Controller_Base_Guest {
    
    function action_index() {
        
        echo 'OK';
        
    }
    
    function action_send() {
        
        $this->user = Model_User::getInstance($this->redis);
        
        if (isset($_POST['id_email'])) {
            if(Validate::email($_POST['id_email']) == true) {
                $pass = $this->user->getPasswordForUserEmail($_POST['id_email']);
                $this->view->result = 'Your pass: '.$pass;
            } elseif (is_int($_POST['id_email'])) {
                $pass = $this->user->getPasswordForUserId($_POST['id_email']);
                $this->view->result = 'Your pass: '.$pass;
            } else {
                $this->view->result = 'You must provide ID or valid email';
            }
        } else {
            $this->view->result = 'You must provide ID or valid email';
        }
        
    }
    
}

?>
