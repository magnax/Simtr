<?php

class Controller_User_Menu extends Controller_Base_User {

    public function action_index() {
        
        $characters = $this->user->characters->find_all();
        $returnedCharacters = array();
        foreach ($characters as $character) {
            
            $new_events = $this->redis->llen("new_events:{$character->id}");
            
            $returnedCharacters[] = array(
                'id' => $character->id,
                'name' => $character->name,
                'location' => $character->spawn_location->name,
                'sex' => $character->sex,
                'project' => '??',
                'age' => $character->created,
                'new_events' => $new_events,
            );
        }
        
        $this->view->characters = $returnedCharacters;
//        
//        foreach($this->user->characters as $ch) {
//            //get character data as array
//            $char = Model_Character::getInstance($this->redis)
//                ->fetchOne($ch, true);
//
//            if ($character_name = Model_ChNames::getInstance($this->redis)
//                ->getName($char['id'], $char['id'])) {
//                $char['name'] =  $character_name;
//            }
//            
//            $location_name = Model_LNames::getInstance($this->redis)->getName($ch, $char['location_id']);
//            $char['location'] = $location_name ? $location_name : $this->dict->getString('unnamed_location');
//            
//            $char['sex'] = $this->dict->getString($char['sex']);
//            
//            if ($char['project_id']) {
//                $char['project'] = 'P '.Model_ProjectManager::getInstance(null, $this->redis)
//                    ->findOneByID($char['project_id'])
//                    ->getProject()->getPercent(1).'%';
//                
//            }
//
//            $this->view->characters[] = $char;
//            
//        }
//
//        $this->view->user = $this->user;

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
        $event->setLocationType($location->getType());
        $event->send();

        $this->request->redirect('u/menu');

    }

    /**
     * ustawia bieżącą postać - wszystkie akcje będą dotyczyły właśnie
     * tej postaci
     *
     * @param <type> $id IDCharacter
     */
    public function action_set($id) {
        
        if (!$this->user->isActive()) {
            $this->redirectError('Cannot play on inactive account', 'user/menu');
        }

        if ($this->user->setCurrentCharacter($id)) {
            $this->request->redirect('user/event');
        } else {
            $this->redirectError('Cannot view events of other player', 'user/menu');
        }

    }
    
}

?>
