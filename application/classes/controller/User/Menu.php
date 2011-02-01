<?php

class Controller_User_Menu extends Controller_Base_User {

    public function action_index() {

        $dict = Model_Dict::getInstance($this->redis, 'pl');
        $lnames = Model_LNames::getInstance($this->redis, $dict);

        $characters = $this->user->getCharacters();

        $this->view->characters = array();
        
        foreach($characters as $ch) {
            $char = Model_Character::getInstance($this->redis)
                ->fetchOne($ch)
                ->toArray();
            $char['location'] = $lnames->getName($char['id'], $char['location_id']);
            $char['sex'] = $dict->getString($char['sex']);
            if ($char['project_id']) {
                $char['project'] = 'P '.Model_Project::getInstance($this->redis)
                    ->findOneByID($char['project_id'])
                    ->getPercent(1).'%';
                
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

}

?>
