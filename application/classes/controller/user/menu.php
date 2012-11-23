<?php

class Controller_User_Menu extends Controller_Base_User {

    public function action_index() {
        
        $characters = $this->user->characters->find_all();
        $returnedCharacters = array();
        foreach ($characters as $character) {
            
            $new_events = $this->redis->llen("new_events:{$character->id}");
            
            $name = ORM::factory('chname')->name($character, $character)->name;
            $location_name = ORM::factory('lname')->name($character, $character->location->id)->name;
            $current_project = RedisDB::getInstance()->get("characters:{$character->id}:current_project");
            if ($current_project) {
                $my_project = ($current_project) ? RedisDB::getJSON("projects:$current_project") : null;

                if ($my_project) {
                    if (!$my_project['time_elapsed']) {
                        $my_project['time_elapsed'] = 0;
                    }
                    $my_project['percent'] = number_format($my_project['time_elapsed'] / $my_project['time'] * 100, 2);
                    $my_project['time_zero'] = $this->game->raw_time;
                    $my_project['speed'] = 1; //for now, will be calculated
                }
                $project = $current_project;
            }
            
            $returnedCharacters[] = array(
                'id' => $character->id,
                'name' => $name ? $name : $character->name,
                'location' => ($location_name) ? $location_name : 'unknown location',
                'sex' => $character->sex,
                'project' => $current_project ? $my_project['percent'].'%' : '-',
                'age' => $character->created,
                'new_events' => $new_events,
                'myproject' => $current_project ? $my_project : null,
                'rip' => ($character->life == 0),
            );
            
        }
        
        $this->view->characters = $returnedCharacters;
        $this->template->chars = $returnedCharacters;

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
    
    public function action_resend_email() {
        
        $this->user->send_activation_email();
        Session::instance()->set('message', 'Email został wysłany, sprawdź skrzynkę');
        Request::current()->redirect('user');    
        
    }
    
}

?>
