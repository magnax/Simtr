<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Character extends Controller_Base_User {
    
    public function action_index() {
        
        $character = new Model_Character(Arr::get($_GET, 'id'));
        
        if (!$character || $character->user_id != $this->user->id) {
            
            $this->redirectError('It is not valid character', 'user/menu');
            
        } else {
            
            $this->session->set('current_character', $character->id);
            $this->request->redirect('events');
            
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
                
                $character = new Model_Character;
                $character->values($_POST);
                $location = Model_Location::getRandomSpawnLocation();
                $character->location_id = $location->id;
                $character->spawn_location_id = $location->id;
                $character->user_id = $this->user->id;
                $character->created = $this->game->getRawTime();
                $character->save();
                
                //create spawn event
                $event_sender = Model_EventSender::getInstance(
                    Model_Event::getInstance(
                        Model_Event::SPAWN, $this->game->raw_time, $this->redis
                    )
                );

                //recipients to lista obiektów klasy Character
                $recipients = $location->getHearableCharacters($this->character);
                $event_sender->addRecipients($recipients);
                $event_sender->setSender($character->id);
                $event_sender->setLocationType($location->class_id);

                $event_sender->send();
                $event_id = $event_sender->getEvent()->getId();

                //lang is set to 'pl' for now, it should be set for every character
                //upon creation with possibility to change later
                Model_EventNotifier::notify($recipients, $event_id, $this->redis, 'pl');
                
                $this->request->redirect('user');
                
            } catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('');
            }
            
        }
        
    }
    
}

?>
