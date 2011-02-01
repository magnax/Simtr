<?php

/**
 * kontroler listy zdarzeń
 */
class Controller_User_Event extends Controller_Base_Character {

    public function action_index() {
        
        $events = $this->character->getEvents();

        $this->view->events = $events;

    }

    public function action_talkall() {
        if (isset($_POST['text']) && $_POST['text']) {
            
            $event = Model_Event::getInstance(Model_Event::TALK_ALL, $this->game->getRawTime(), $this->redis);       
            $event->setText($_POST['text']);
            //recipients to lista obiektów klasy Character
            $event->addRecipients($this->location->getAllHearableCharacters());
            $event->setSender($this->character->getId());

            $event->send();

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
        $event = Model_Event::getInstance(Model_Event::PUT_RAW, $this->game->getRawTime(), $this->redis);
        $event->setResource($_POST['res_id'], $_POST['amount']);
        //recipients to lista obiektów klasy Character
        $event->addRecipients($this->location->getAllVisibleCharacters($this->character->getPlaceType()));
        $event->setSender($this->character->getId());
        $event->send();

        $this->request->redirect('events');
        
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
        $event = Model_Event::getInstance(Model_Event::GET_RAW, $this->game->getRawTime(), $this->redis);
        $event->setResource($_POST['res_id'], $_POST['amount']);
        //recipients to lista obiektów klasy Character
        $event->addRecipients($this->location->getAllVisibleCharacters($this->character->getPlaceType()));
        $event->setSender($this->character->getId());
        $event->send();

        $this->request->redirect('events');
        
    }
    
}

?>
