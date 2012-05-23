<?php

class Controller_User_Menu extends Controller_Base_User {

    public function action_index() {

        $lnames = Model_LNames::getInstance($this->redis, $this->dict);
        
        $characters = $this->user->getCharacters();

        $this->view->characters = array();
        
        foreach($characters as $ch) {
            $char = Model_Character::getInstance($this->redis)
                ->fetchOne($ch)
                ->toArray();
            $lnames->setCharacter($char['id']);
            $char['location'] = $lnames->getName($char['location_id']);
            $char['sex'] = $this->dict->getString($char['sex']);
            if ($char['project_id']) {
                $char['project'] = 'P '.Model_ProjectManager::getInstance(null, $this->redis)
                    ->findOneByID($char['project_id'])
                    ->getProject()->getPercent(1).'%';
                
            }

            $this->view->characters[] = $char;
            
        }

        $this->view->user = $this->user;

    }

    public function action_logout() {

        if ($this->user->logout()) {
            $this->session->delete('authkey');
            $this->request->redirect('/');
        }

    }

    /**
     * formularz tworzenia nowej postaci
     */
    public function action_newform() {

    }
    
    /**
     * sprawdzenie i utworzenie nowej postaci
     * "spawning" w losowej lokacji
     * przypisanie początkowych współczynników
     */
    public function action_new() {

        if (!isset ($_POST['name']) || !isset ($_POST['sex'])) {
            $this->redirectError('Musi być podane imię oraz płeć', '/u/char/newform');
        }

        $character = Model_Character::getInstance($this->redis);
        $character->setName($_POST['name']);
        $character->setSex($_POST['sex']);
        $character->setIDUser($this->user->getID());

        //tak będzie prawidłowo a na razie wszyscy w jednej lokacji ;)
        //$id_location = $this->redis->srandmember('global:locations');
        $id_location = 1;

        $character->createNew($this->game->getRawTime(), $id_location);

        $character->save();

        $location = Model_Location::getInstance($this->redis)
            ->findOneByID($id_location, $character->getId());

        $location->appendCharacter($character->getId());
        $this->user->appendCharacter($character->getId());
        $this->user->save();

        $event = Model_Event::getInstance(Model_Event::SPAWN,
            $this->game->getRawTime(),
            $this->redis);

        $event->setNewspawnID($character->getId());
        $event->setLocation($location);
        //recipients to lista obiektów klasy Character
        $event->addRecipients($location->getAllVisibleCharacters('loc'));
        $event->setSender($character->getId());
        $event->send();

        $this->request->redirect('u/menu');

    }

}

?>
