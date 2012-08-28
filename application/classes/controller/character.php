<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Character extends Controller_Base_User {
    
    public function action_index() {
        if ($_GET && $_GET['id']) {
            $character = ORM::factory('character', $_GET['id']);
            if (!$character || $character->user_id != $this->user->id) {
                $error_msg = 'It is not valid character';
            } else {
                $this->session->set('current_character', $character->id);
                Request::current()->redirect('events');
            }
        } else {
            $error_msg = 'You didn\'t choose character';
        }
        
        $this->redirectError($error_msg, 'user/menu');
    }
    
    public function action_new() {
        
        $this->view->bind('errors', $errors);
        
        if ($_POST) {
            
            try {
                $character = new Model_Character;
                $character->values($_POST);
                $location_id = 5;
                $character->location_id = $location_id; //will be random location
                $character->spawn_location_id = $location_id;
                $character->user_id = $this->user->id;
                $character->created = $this->game->getRawTime();
                $character->save();
                $this->request->redirect('user');
            } catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('');
            }
            
        }
        
    }
    
}

?>
