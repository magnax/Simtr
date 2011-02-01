<?php

class Controller_User_Location extends Controller_Base_Character {

    public function action_index() {

        $l = Model_Location::getInstance($this->redis)->
            findOneByID($this->character->location_id, $this->character->id)->
                toArray();
        
        $resources = array();
        foreach ($l['resources'] as $res) {
            $r = Model_Resource::getInstance($this->redis)->
                findOneById($res)->
                toArray();
            $resources[] = $r;
        }
        $l['resources'] = $resources;
        $this->view->l = $l;

    }

    public function action_nameform($id) {

        $this->view->name = $this->lnames->getName($this->character->getId(), $id);
        $this->view->location_id = $this->character->location_id;


    }

    public function action_change() {

        $this->lnames->setName($this->character->getId(), $_POST['location_id'], $_POST['name']);
        $this->request->redirect('events');
        
    }

    public function action_objects() {
        $this->view->raws = $this->location->getRaws();
    }

}

?>
