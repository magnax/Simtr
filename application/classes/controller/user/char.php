<?php

/**
 * kontroler postaci
 * - tworzenie nowej postaci
 */
class Controller_User_Char extends Controller_Base_Character {

    public function action_nameform($id) {
        $character = Model_Character::getInstance($this->redis)->fetchOne($id, true);
        $this->view->character_id = $id;
        $name = $this->character->getChname($id);
        if (!$name) {
            $name = Model_Dict::getInstance($this->redis)->getString($this->character->getUnknownName($id));
        }
        $this->view->name = $name;
    }

    public function action_namechange() {

        Model_ChNames::getInstance($this->redis)->setName($this->character->getId(), $_POST['character_id'], $_POST['name']);
        $this->character->fetchChnames();

        $this->request->redirect('user/event');
        
    }
    
    public function action_talkto() {

        require_once APPPATH . 'modules/elephant/classes/client.php';
        
        if (isset($_POST['text']) && $_POST['text']) {
            
            $recipient = new Model_Character($_POST['character_id']);
            
            $event_sender = Model_EventSender::getInstance(
                Model_Event::getInstance(
                    Model_Event::TALK_TO, $this->game->raw_time, $this->redis
                )
            );
            
            $recipients = $this->location->getHearableCharacters();
            
            $event_sender->setText($_POST['text']);           
            //recipients to lista obiektów klasy Character
            $event_sender->setRecipient($recipient->id);
            $event_sender->setSender($this->character->id);
            $event_sender->addRecipients($recipients);
            
            $event_sender->send();
            
            $event_id = $event_sender->getEvent()->getId();
    
            $elephant = new Client($this->server_uri);
            $elephant->init();
            
            $event_dispatcher = Model_EventDispatcher::getInstance($this->redis, $this->lang);
            
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
            
        }

        $this->request->redirect('events');
    }
}

?>
