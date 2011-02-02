<?php

/**
 * kontroler postaci
 * - tworzenie nowej postaci
 */
class Controller_User_Char extends Controller_Base_Character {

    public function action_nameform($id) {
        $character = Model_Character::getInstance($this->redis)->fetchOne($id);
        $this->view->character_id = $id;
        //$this->view->name = $this->character->getChname($id);
        $this->view->name = $this->chnames->getName($this->character->getId(), $id);
    }

    public function action_namechange() {

        $this->chnames->setName($this->character->getId(), $_POST['character_id'], $_POST['name']);
        $this->request->redirect('events');
        
    }

    public function action_talkto() {

        if (isset($_POST['text']) && $_POST['text']) {
            $recipient = Model_Character::getInstance($this->redis)
                ->fetchOne($_POST['character_id']);

            //$location = Model_Location::getInstance($this->redis)
            //    ->findOneByID($this->character->getIDLocation(), $this->character->getID());

            $event = Model_Event::getInstance(Model_Event::TALK_TO,
                $this->game->getRawTime(),
                $this->redis);

            $event->setText($_POST['text']);           
            //recipients to lista obiektów klasy Character
            $event->addRecipients(array($recipient));
            $event->setSender($this->user->getCurrentCharacter());
            $event->send();
    
        }

        $this->request->redirect('events');
    }
}

?>
