<?php

class Controller_User_Location extends Controller_Base_Character {

    public function action_index() {

        //get resources list
        $resources = $this->location->getResources();
        
        foreach ($resources as $res) {
            $r = Model_Resource::getInstance($this->redis)->
                findOneById($res)->
                toArray();
            $resources[] = $r;
        }
        
        $location = $this->location->toArray();
        
        $location['resources'] = $resources;        

        $location['exits'] = $location->getExits($this->character);
        
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
        $this->view->items = $this->location->getItems();
    }

    public function action_getitem($item_id) {
        
        /**
         * @todo check if user has this item 
         */
        $this->location->putItem($item_id);
        $this->character->addItem($item_id);
        //generate event
        $event = Model_EventSender::getInstance(
            Model_Event::getInstance(
                Model_Event::GET_ITEM, $this->game->raw_time, $this->redis
            )
        );

        $event->setSender($this->character->getId());
        $event->setItem($item_id);

        $event->addRecipients($this->location->getAllVisibleCharacters($this->character->getPlaceType()));
        $event->send();
        
        $this->request->redirect('user/event');
    }
    
}

?>
