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
        
        $this->view->locationtype = $this->location->locationtype_id;
        
        if ($this->location->locationtype_id == 1) {
            $this->view->exits = array();
            $this->view->location['used_slots'] = 999;
            $this->view->location['res_slots'] = $this->location->town->slots;
            $this->view->location['resources'] = $location_resources;
        } else {
            $this->view->exits = array();
        }
        
        if ($this->location->parent_id) {
            $location_name = ORM::factory('lname')->name($this->character->id, $this->location->parent_id)->name;
            $this->view->doors = array(
                'id' => $this->location->parent_id,
                'name' => Utils::getLocationName($location_name),
            );
        } else {
            $this->view->doors = null;
        }
        
    }

    public function action_objects() {
        $this->view->raws = $this->location->getRaws();
        $this->view->items = $this->location->getItems();
        $corpses = $this->location->getCorpses();
       
        $this->view->corpses = array();
        
        foreach ($corpses as $corpse) {
            $name = ORM::factory('chname')->name($this->character->id, $corpse->character_id)->name;
            if (!$name) {
                if ($ch == $this->character->id) {
                    //myself
                    $name = $this->character->name;
                } else {
                    $name = ORM::factory('character')->getUnknownName($corpse->character_id, $this->lang);
                }
            }
            $this->view->corpses[] = array(
                'name' => $name,
                'id' => $corpse->id,
                'character_id' => $corpse->character_id,
            );
        }
        
    }

    public function action_getitem() {
        
        $item_id = $this->request->param('id');
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

        $event->addRecipients($this->location->getVisibleCharacters());
        $event->send();
        
        $this->request->redirect('events');
    }
    
    /**
     * enter the building (or just another location)
     */
    public function action_enter() {
        
        $location_id = $this->request->param('id');
        /**
         * @todo check if user may enter this location
         * (locked, too much weight etc.)
         */
        
        //generate event
        $event = Model_EventSender::getInstance(
            Model_Event::getInstance(
                Model_Event::ENTER_LOCATION, $this->game->raw_time, $this->redis
            )
        );
        
        //first get recipients from current loc
        $event->addRecipients($this->location->getVisibleCharacters());
        
        $this->character->location_id = $location_id;
        $this->character->save();
        
        $event->setSender($this->character->getId());
        $event->setLocationId($location_id);

        $event->send();
        
        $this->request->redirect('events');
        
    }
    
}

?>
