<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Location extends Controller_Base_Character {

    public function action_index() {

        //get proper location object from factory
        //$location = Model_LocationFactory::getInstance($this->location);
        
        //get resources list
        $resources = $this->location->resources->find_all()->as_array();
        
        $location_resources = array();
        foreach ($resources as $r) {
            $location_resources[] = array(
                'id' => $r->id,
                'name'=> $r->name
            );
        }

        $this->view->location = $this->location->as_array();
        $this->view->location['used_slots'] = 999;
        $this->view->location['res_slots'] = $this->location->town->slots;
        $this->view->location['resources'] = $location_resources;
        
        if ($this->location->locationtype_id == 1) {
            $this->view->exits = array();
        } else {
            $this->view->exits = array();
        }
        
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
