<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Character extends Controller_Base_User {
    
    public function action_index() {
        
        $character = new Model_Character(Arr::get($_GET, 'id'));
        
        if (!$character || $character->user_id != $this->user->id) {
            
            $this->redirectError('It is not valid character', 'user/menu');
            
        } else {
            
            $this->session->set('current_character', $character->id);
            $this->redirect('events');
            
        }
        
    }
    
    public function action_new() {
        
        //redirect if user not activated
        if (!$this->user->active) {
            $this->redirectError('Twoje konto jest nieaktywne!!', 'user');
        }
        
        $this->view->bind('errors', $errors);
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            try {
                
                //get random spawn location
                $location = Model_Location::getRandomSpawnLocation();
                
                //get location character before creating new, so spawn event
                //will not be visible to spawned character
                $location_characters = $location->getHearableCharacters();
                
                $character = new Model_Character;
                $character->values($_POST);
                $character->location_id = $location->id;
                $character->spawn_location_id = $location->id;
                $character->user_id = $this->user->id;
                $character->created = $this->game->getRawTime();
                $character->save();
                
                //create spawn event
                $event = new Model_Event();
                $event->type = Model_Event::SPAWN;
                $event->date = $this->game->getRawTime();

                $event->add('params', array('name' => 'sndr', 'value' => $character->id));

                $event->save();

                $event->notify($location_characters);
                
                //create arriving at location event
                //the same event will be generated after arriving to location
                //and after spawning in location
                $event = new Model_Event();
                $event->type = Model_Event::ARRIVE_INFO;
                $event->date = $this->game->getRawTime();

                $event->add('params', array('name' => 'sndr', 'value' => $character->id));
                $event->add('params', array('name' => 'location_type', 'value' => $location->class_id));
                $event->add('params', array('name' => 'characters_count', 'value' => count($location_characters)));

                $event->save();

                //notify only newspawned character
                $event->notify(array($character->id));
                
                $this->redirect('user');
                
            } catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('');
            }
            
        }
        
    }
    
}

?>
