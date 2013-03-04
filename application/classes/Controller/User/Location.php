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
            $location_name = ORM::factory('LName')->name($this->character->id, $this->location->parent_id)->name;
            $this->view->doors = array(
                'id' => $this->location->parent_id,
                'name' => Utils::getLocationName($location_name),
            );
        } else {
            $this->view->doors = null;
        }
        
    }

    public function action_objects() {
        
        $location_lockable = $this->location->locationtype->lockable;

        if ($location_lockable) {
            
            $lock = $this->location->lock;
            $max_lock_level = $this->location->locationtype->max_lock_level;
            $this->view->lock = $lock;
            $this->view->has_key = $this->character->hasKey($lock->nr);
            
            //one can upgrade lock only if it's not on highest level
            //and when there's no upgrading project
            $this->view->can_upgrade_lock = 
                ($lock->locktype->level < $max_lock_level) && 
                !$this->location->hasProjectType(Model_Project::TYPE_LOCKBUILD);
                        
        }
        
        $this->view->lockable = $location_lockable;
        $this->view->max_lock_level = $max_lock_level;
        
        $this->view->locationtype = $this->location->locationclass->name;
        $this->view->raws = $this->location->getRaws();
        $this->view->items = $this->location->getItems();
        $this->view->notes = $this->location->getNotes();
        $corpses = $this->location->getCorpses();
       
        $this->view->corpses = array();
        
        foreach ($corpses as $corpse) {
            $name = ORM::factory('ChName')->name($this->character->id, $corpse->character_id)->name;
            if (!$name) {
                //not necesary for own corpse, because character can't seen his corpse, but... :)
                if ($corpse->character_id == $this->character->id) {
                    //myself
                    $name = $this->character->name;
                } else {
                    $name = ORM::factory('Character')->getUnknownName($corpse->character_id, $this->lang);
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
        
        $this->redirect('events');
    }
    
    /**
     * enter the building (or just another location)
     */
    public function action_enter() {
        
        $error = null;
        
        $dest_location_id = $this->request->param('id');
        $exit_location_id = $this->character->location_id;
        /**
         * @todo check if user may enter this location
         * (locked, too much weight etc.)
         */
        
        $dest_location = new Model_Location($dest_location_id);
        $exit_location = new Model_Location($exit_location_id);
        $lock = $exit_location->lock;
        
        if ($lock->locked && !$this->character->hasKey($lock->nr)) {
            $error = 'Nie możesz wyjść, zamknięte';
        }
        
        if ($dest_location->lock->locked && !$this->character->hasKey($dest_location->lock->nr)) {
            $error = 'Nie możesz wejść, zamknięte';
        }
        
        if (!$error) {
        
            //if user is working on the project, leave it before enter
            $this->character->leaveCurrentProject($this->redis, $this->game->raw_time);

            //generate event
            $event_sender = Model_EventSender::getInstance(
                Model_Event::getInstance(
                    Model_Event::ENTER_LOCATION, $this->game->raw_time, $this->redis
                )
            );

            //first get recipients from current loc
            $current_recipients = $this->location->getVisibleCharacters();

            $dest_recipients = $dest_location->getVisibleCharacters();

            $recipients = array_merge($current_recipients, $dest_recipients);

            $event_sender->addRecipients($recipients);

            $this->character->location_id = $dest_location_id;
            $this->character->save();

            $event_sender->setSender($this->character->getId());
            $event_sender->setLocationId($dest_location_id);
            $event_sender->setExitLocationId($exit_location_id);

            $event_sender->send();

            $event_id = $event_sender->getEvent()->getId();
            Model_EventNotifier::notify($recipients, $event_id, $this->redis, $this->lang);
        
        } else {
            //there was some error, set error message
            Session::instance()->set('error', $error);
        }
        
        $this->redirect('events');
        
    }
    
    /**
     * burying dead bodies
     * I'm not sure whether this method should belongs to location controller
     */
    public function action_bury() {
        
        $body_id = $this->request->param('id');
        
        $body = new Model_Corpse($body_id);
        $body->location_id = null;
        $body->save();
        
        $project_manager = Model_ProjectManager::getInstance(
            Model_Project::getInstance(Model_Project::TYPE_BURY, $this->redis)//;
        );

        $data = array(
            'name'=>'Zakopywanie ciała',
            'owner_id'=>$this->character->id,
            'time'=>3600,
            'type_id'=>Model_Project::TYPE_BURY,
            'place_type'=>$this->location->locationtype_id,
            'place_id'=>$this->location->id,
            'body_id'=>$body_id,
            'character_id' => $body->character_id,
            'created_at'=>$this->game->getRawTime()
        );

        $project_manager->set($data);
        $project_manager->save();

        $this->location->addProject($project_manager->getId(), $this->redis);

        $this->redirect('events');

    }
    
}

?>
