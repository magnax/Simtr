<?php defined('SYSPATH') or die('No direct script access.');

/**
 * kontroler listy zdarzeń
 */
class Controller_Events extends Controller_Base_Character {
    
    public function action_index($page = 1) {

        $events = Model_Character_Redis::getEvents($this->character->id, $this->redis, $this->lang, $page);
        $this->view->first_new_event = $this->redis->lpop("new_events:{$this->character->id}");
        $this->redis->del("new_events:{$this->character->id}");
        $this->view->events = $events;

    }

    public function action_events2($page = 1) {
        $this->template->set_filename('templates/new_character');
        $events = Model_Character_Redis::getEvents($this->character->id, $this->redis, $this->lang, $page);
        $this->template->user_token = 'abc';
    }

    public function action_talkall() {
        
        require_once APPPATH . 'modules/elephant/classes/client.php';
        
        if (isset($_POST['text']) && $_POST['text']) {
            
            $event_sender = Model_EventSender::getInstance(
                Model_Event::getInstance(
                    Model_Event::TALK_ALL, $this->game->raw_time, $this->redis
                )
            );

            $event_sender->setText($_POST['text']);
            
            //recipients to lista obiektów klasy Character
            $recipients = $this->location->getHearableCharacters($this->character);
            $event_sender->addRecipients($recipients);
            $event_sender->setSender($this->character->id);

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

        if ($this->request->is_ajax()) {
            $this->auto_render = false;
            return;
        }
        
        $this->request->redirect('events');
        
    }

    public function action_put_raw($id) {
        $inventory = $this->character->getRaws();
        $this->view->res = $inventory[$id];
        $this->view->character = $this->template->character;
    }

    public function action_put() {

        $id = $_POST['res_id'];
        $amount = $_POST['amount'];

        $this->character->putRaw($id, $amount);
        $this->location->addRaw($id, $amount);

        //wysłanie eventu
        $event = Model_EventSender::getInstance(
            Model_Event::getInstance(
                Model_Event::PUT_RAW, $this->game->getRawTime(), $this->redis
            )
        );
        $event->setResource($_POST['res_id'], $_POST['amount']);
        //recipients to lista obiektów klasy Character
        $event->addRecipients($this->location->getAllVisibleCharacters($this->character->getPlaceType()));
        $event->setSender($this->character->getId());
        $event->send();

        $this->request->redirect('user/event');
        
    }

    public function action_get_raw($id) {
        $raws = $this->location->getRaws();
        $this->view->res = $raws[$id];
        $this->view->character = $this->template->character;
    }

    public function action_get() {

        $id = $_POST['res_id'];
        $amount = $_POST['amount'];

        $this->location->putRaw($id, $amount);
        $this->character->addRaw($id, $amount);

        //wysłanie eventu
        $event = Model_EventSender::getInstance(
            Model_Event::getInstance(
                Model_Event::GET_RAW, $this->game->raw_time, $this->redis
            )
        );
        $event->setResource($_POST['res_id'], $_POST['amount']);
        //recipients to lista obiektów klasy Character
        $event->addRecipients($this->location->getAllVisibleCharacters($this->character->getPlaceType()));
        $event->setSender($this->character->getId());
        $event->send();

        $this->request->redirect('user/event');
        
    }

    public function action_give_raw($id) {
        $raws = $this->character->getRaws();
        $all_characters = $this->location
            ->getAllHearableCharacters($this->character, true);
        $this->view->all_characters = array();
        foreach ($all_characters as $char) {
            if ($char['id'] != $this->character->getId()) {
                $name = $this->character->getChname($char['id']);
                if (!$name) {
                    $name = Model_Dict::getInstance($this->redis)->getString($this->character->getUnknownName($char['id']));
                }
                $this->view->all_characters[$char['id']] = $name;
            }
        }
        $this->view->res = $raws[$id];
        $this->view->character = $this->template->character;
    }

    public function action_give() {

        $dest_character = Model_Character::getInstance($this->redis, $this->character->chnames)
            ->fetchOne($_POST['character_id']);
        $this->character->putRaw($_POST['res_id'], $_POST['amount']);
        $dest_character->addRaw($_POST['res_id'], $_POST['amount']);

        //wysłanie eventu
        $event = Model_EventSender::getInstance(
            Model_Event::getInstance(
                Model_Event::GIVE_RAW, $this->game->raw_time, $this->redis
            )
        );
        $event->setResource($_POST['res_id'], $_POST['amount']);
        //recipients to lista obiektów klasy Character
        $event->addRecipients($this->location->getAllVisibleCharacters($this->character->getPlaceType()));
        $event->setSender($this->character->getId());
        $event->setRecipient($dest_character->getId());
        $event->send();

        $this->request->redirect('user/event');

    }

}

?>
