<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Location extends Controller_Base_Character {

    public function action_index() {

        $this->view->location = $this->location; //->as_array();
        
        $this->view->locationtype = $this->location->locationtype_id;
        
        if ($this->location->locationtype_id == 1) {    //town
            $this->view->exits = $this->location->get_exits($this->character);
            $this->view->used_slots = 999;
            $this->view->res_slots = $this->location->town->slots;
            
            //get resources list
            $resources = $this->location->resources->find_all()->as_array();

            $location_resources = array();
            foreach ($resources as $r) {
                $location_resources[] = array(
                    'id' => $r->id,
                    'name'=> $r->name
                );
            }
        
            $this->view->resources = $resources;
        } else {
            $this->view->exits = array();
        }
        
        if ($this->location->parent_id) {
            $location_name = $this->location->get_lname($this->character->id); //ORM::factory('LName')->name($this->character->id, $this->location->parent_id)->name;
            $this->view->doors = array(
                'id' => $this->location->parent_id,
                'name' => $this->location->parent->get_lname($this->character->id), //Utils::getLocationName($location_name),
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
             
            $this->view->max_lock_level = $max_lock_level;
            
        }
        
        $this->view->lockable = $location_lockable;
        
        $this->view->locationtype = $this->location->locationclass->name;
        $this->view->raws = $this->location->getRaws();
        $this->view->items = $this->location->getItems();
        $this->view->notes = $this->location->getNotes();
        $corpses = $this->location->getCorpses();
        
        $this->view->machines = $this->location->machines->find_all();
       
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
        $item = new Model_Item($item_id);
        
        try {

            $this->character->get_item($item, $this->location);

            //wysłanie eventu
            $event = new Model_Event();
            $event->type = Model_Event::GET_ITEM;
            $event->date = $this->game->getRawTime();

            $event->add('params', array('name' => 'sndr', 'value' => $this->character->id));
            $event->add('params', array('name' => 'item_id', 'value' => $item->id));
            $event->add('params', array('name' => 'item_points', 'value' => $item->itemtype->kind . $item->points_percent()));

            $event->save();

            $event->notify($this->location->getVisibleCharacters());
            
        } catch (Exception $e){
            
            $this->redirectError($e->getMessage(), 'user/location/objects');
            
        }
        
        $this->redirect('events');
        
    }
    
    /**
     * enter the building (or just another location)
     */
    public function action_enter() {
        
        try {
            
            $dest_location = new Model_Location($this->request->param('id'));
            $exit_location = $this->location;
            
            //first get recipients from current loc
            $current_recipients = $this->location->getVisibleCharacters();
            
            //then - recipients from destination location
            $dest_recipients = $dest_location->getVisibleCharacters();
            
            $this->character->enter_location($dest_location);

            //merge all recipients in one list
            $recipients = array_merge($current_recipients, $dest_recipients);
            
            //wysłanie eventu
            $event = new Model_Event();
            $event->type = Model_Event::ENTER_LOCATION;
            $event->date = $this->game->getRawTime();

            $event->add('params', array('name' => 'sndr', 'value' => $this->character->id));
            $event->add('params', array('name' => 'locid', 'value' => $dest_location->id));
            $event->add('params', array('name' => 'exit_id', 'value' => $exit_location->id));

            $event->save();

            $event->notify($recipients);
            
        } catch (Exception $e) {
            
            $this->redirectError($e->getMessage(), 'events');
            
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
