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

        if (isset($_POST['text']) && $_POST['text']) {
            $recipient = Model_Character::getInstance($this->redis, $this->character->chnames)
                ->fetchOne($_POST['character_id']);

            //$location = Model_Location::getInstance($this->redis)
            //    ->findOneByID($this->character->getIDLocation(), $this->character->getID());

            $event = Model_EventSender::getInstance(
                Model_Event::getInstance(
                    Model_Event::TALK_TO, $this->game->raw_time, $this->redis
                )
            );

            $event->setText($_POST['text']);           
            //recipients to lista obiektów klasy Character
            $event->setRecipient($recipient->getId());
            $event->setSender($this->character->getId());
            $event->addRecipients($this->location->getAllHearableCharacters(false, $this->character->chnames));
            $event->send();
    
        }

        $this->request->redirect('user/event');
    }
}

?>
