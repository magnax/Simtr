<?php

class Controller_User_Menu extends Controller_Base_User {

    public function action_index() {
        
        $chnames = Model_ChNames::getInstance($this->redis, $this->dict);
        
        $characters = $this->user->getCharacters();

        $this->view->characters = array();
        
        foreach($characters as $ch) {
            $char = Model_Character::getInstance($this->redis)
                ->fetchOne($ch)
                ->toArray();
            $this->lnames->setCharacter($char['id']);
            $char['name'] = $chnames->getName($char['id'], $char['id']);
            $char['location'] = $this->lnames->getName($char['location_id']);
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

        //generate event & message
        $event = Model_EventSender::getInstance(
            Model_Event::getInstance(
                Model_Event::SPAWN, $this->game->getRawTime(), $this->redis
            )
        );
        
        if (!Arr::get($_POST, 'name') || !Arr::get($_POST, 'sex')) {
            $this->redirectError('Musi być podane imię oraz płeć', '/u/menu/newform');
        }

        //określenie lokacji początkowej
            //tak będzie praget widłowo a na razie wszyscy w jednej lokacji ;)
            //$id_location = $this->redis->srandmember('global:locations');
        $id_location = 1;
        
        $character_data = array(
            'name' => Arr::get($_POST, 'name'),
            'sex' => Arr::get($_POST, 'sex'),
            'location_id' => $id_location,
            'spawn_date' => $this->game->getRawTime(),
            'user_id' => $this->user->getID(),
        );
        
        //create (and save) new character
        $character = Model_Character::getInstance($this->redis)
            ->createNew($character_data);

        $location = Model_Location::getInstance($this->redis)
            ->findOneByID($id_location, $character->getId());

        //append chracter to location
        $location->appendCharacter($character->getId());
        
        //append character to user
        $this->user->appendCharacter($character->getId());

        

        //recipients to lista obiektów klasy Character
        $event->addRecipients($location->getAllVisibleCharacters('loc'));
        $event->setSender($character->getId());
        $event->send();

        $this->request->redirect('u/menu');

    }

}

?>
