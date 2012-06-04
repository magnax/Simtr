<?php

/**
 * kontroler postaci
 * - tworzenie nowej postaci
 */
class Controller_User_Char extends Controller_Base_Character {

    public function action_nameform($id) {
        $character = Model_Character::getInstance($this->redis, $this->character->chnames)->fetchOne($id);
        $this->view->character_id = $id;
        $this->view->name = $this->character->chnames->getName($this->character->getId(), $id);
    }

    public function action_namechange() {

        $this->character->chnames->setName($this->character->getId(), $_POST['character_id'], $_POST['name']);
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
