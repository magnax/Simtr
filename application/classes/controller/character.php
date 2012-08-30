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
        
        require_once APPPATH . 'modules/elephant/classes/client.php';
        
        $this->view->bind('errors', $errors);
        
        if ($_POST) {
            
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

                $elephant = new Client($this->server_uri);
                $elephant->init();

                $event_dispatcher = Model_EventDispatcher::getInstance($this->redis, 'pl');

                foreach ($recipients as $recipient) {

                    $notifyChar = new Model_Character($recipient);

                    if ($notifyChar->connectedChar($this->redis)) {

                        $data = json_encode(array(
                            'name' => 'push_event',
                            'args' => array(
                                'event_id'=> $event_id,
                                'char_id' => $recipient,
                                'text' => $event_dispatcher->formatEvent($event_id, $recipient),
                            )
                        ));               

                        $elephant->send(Client::TYPE_EVENT, null, null, $data);
                        echo 'notifying char: '.$recipient;

                    } else {

                        $this->redis->rpush("new_events:$recipient", $event_id);

                        if ($notifyChar->connectedUser($this->redis)) {
                            //user is watching, add to event query
                            $data = json_encode(array(
                                'name' => 'push_user_event',
                                'args' => array(
                                    'user_id' => $notifyChar->user_id,
                                    'char_id' => $recipient,
                                    'event_id' => $event_sender->getEvent()->getId(),
                                )
                            ));

                            $elephant->send(Client::TYPE_EVENT, null, null, $data);
                            echo 'notifying user of: '.$recipient;
                        }
                    }

                }
                
                $this->request->redirect('user');
                
            } catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('');
            }
            
        }
        
    }
    
}

?>
