<?php

class Controller_User_Location extends Controller_Base_Character {

    public function action_index() {

        $l = Model_Location::getInstance($this->redis)->
            fetchOne($this->character->location_id);
        
        
        
        $resources = array();
        foreach ($l->resources as $res) {
            $r = Model_Resource::getInstance($this->redis)->
                findOneById($res)->
                toArray();
            $resources[] = $r;
        }
        
        $location = $l->toArray();
        
        $location['resources'] = $resources;        
        
        $location['exits'] = $l->getExits($this->character);
        
        $this->view->l = $location;
    }

    public function action_nameform($id) {

        $this->view->name = $this->lnames->getName($id);
        $this->view->location_id = $id;


    }

    public function action_change() {

        $this->lnames->setName($_POST['location_id'], $_POST['name']);
        $this->request->redirect('events');
        
    }

    public function action_objects() {
        $this->view->raws = $this->location->getRaws();
    }

}

?>
