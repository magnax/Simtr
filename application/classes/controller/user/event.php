<?php

/**
 * kontroler listy zdarzeń
 */
class Controller_User_Event extends Controller_Base_Character {

    public function action_index($page = 1) {

        // Be sure to only profile if it's enabled
        if (Kohana::$profiling === TRUE) {
            // Start a new benchmark
            $benchmark = Profiler::start('Character Controller', __FUNCTION__);
        }

        $events = $this->character->getEvents($page);

        if (isset($benchmark)) {
            // Stop the benchmark
            Profiler::stop($benchmark);
        }

        $this->view->events = $events;

    }

    public function action_talkall() {
        if (isset($_POST['text']) && $_POST['text']) {
            
            $event = Model_EventSender::getInstance(
                Model_Event::getInstance(
                    Model_Event::TALK_ALL, $this->game->raw_time, $this->redis
                )
            );
            $event->setText($_POST['text']);
            //recipients to lista obiektów klasy Character
            $event->addRecipients($this->location->getAllHearableCharacters($this->character));
            $event->setSender($this->character->getId());

            $event->send();

        }

        $this->request->redirect('user/event');
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
