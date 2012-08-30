<?php

class Controller_User_Menu extends Controller_Base_User {

    public function action_index() {
        
        $characters = $this->user->characters->find_all();
        $returnedCharacters = array();
        foreach ($characters as $character) {
            
            $new_events = $this->redis->llen("new_events:{$character->id}");
            
            $name = ORM::factory('chname')->name($character, $character)->name;
            
            $returnedCharacters[] = array(
                'id' => $character->id,
                'name' => $name ? $name : $character->name,
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
