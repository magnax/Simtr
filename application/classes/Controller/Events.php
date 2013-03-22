<?php defined('SYSPATH') or die('No direct script access.');

/**
 * kontroler listy zdarzeń
 */
class Controller_Events extends Controller_Base_Character {
    
    public function action_index() {
        
        $page = $this->request->param('page', 1);

        $events = $this->character->getEvents($page);
        $this->view->first_new_event = $this->redis->lpop("new_events:{$this->character->id}");
        $this->redis->del("new_events:{$this->character->id}");
        $this->view->events = $events;
        $this->view->pagination = $this->character->getPagination();

    }

    public function action_talkall() {
        
        if (HTTP_Request::POST == $this->request->method() && $this->request->post('text')) {
            
            $event = new Model_Event();
            
            $text = strip_tags($this->request->post('text'));
            if (Auth::instance()->logged_in('admin') && $text[0] == '!') {
                $event->type = Model_Event::GOD_TALK;
                $text = substr($text, 1, strlen($text)-1);
                $recipients = Model_Character::getAllCharactersIds();
            } else {
                $event->type = Model_Event::TALK_ALL;
                $recipients = $this->location->getHearableCharacters($this->character);
            }

            $event->date = $this->game->raw_time;
            
            $event->add('params', array('name' => 'sndr', 'value' => $this->character->id));
            $event->add('params', array('name' => 'text', 'value' => $text));
            
            $event->save();
            
            $event->notify($recipients);

        }

        if ($this->request->is_ajax()) {
            $this->auto_render = false;
            echo 'success';
            return;
        }
        
        $this->redirect('events');
        
    }

    public function action_put_raw() {
        
        //get all raws from the character
        $inventory = $this->character->getRaws();
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            $id = $this->request->post('res_id');
            $amount = $this->request->post('amount');
            
            if (!isset($inventory[$id]) || ($amount > $inventory[$id]['amount'])) {
                $this->redirectError('Nieprawidłowy materiał lub nie posiadasz tyle', 'user/inventory');
            }

            $this->character->putRaw($id, $amount);
            $this->location->addRaw($id, $amount);

            //wysłanie eventu
            $event = new Model_Event();
            $event->type = Model_Event::PUT_RAW;
            $event->date = $this->game->getRawTime();
            
            $event->add('params', array('name' => 'sndr', 'value' => $this->character->id));
            $event->add('params', array('name' => 'res_id', 'value' => $id));
            $event->add('params', array('name' => 'amount', 'value' => $amount));
            
            $event->save();
            
            $event->notify($this->location->getVisibleCharacters());
            
            $this->redirect('events');
            
        }
        
        $id = $this->request->param('id');
        
        if (!$this->character->getRawAmount($id)) {
            $this->redirectError('Nie posiadasz tego materiału', 'user/inventory');
        }
        
        $this->view->res = $inventory[$id];
        $this->view->character = $this->template->character;
    }

    public function action_get_raw() {
        
        if (HTTP_Request::POST == $this->request->method()) {
        
            $id = $this->request->post('res_id');
            $amount = $this->request->post('amount');

            try {
                $this->character->get_raw_from_location($this->location, $id, $amount);

                //wysłanie eventu
                $event = new Model_Event();
                $event->type = Model_Event::GET_RAW;
                $event->date = $this->game->getRawTime();

                $event->add('params', array('name' => 'sndr', 'value' => $this->character->id));
                $event->add('params', array('name' => 'res_id', 'value' => $id));
                $event->add('params', array('name' => 'amount', 'value' => $amount));

                $event->save();

                $event->notify($this->location->getVisibleCharacters());

                $this->redirect('events');
                
            } catch (Exception $e) {
                $this->redirectError($e->getMessage(), 'user/location/objects');
            }
            
        }
        
        $id = $this->request->param('id');
        $location_raws = $this->location->getRaws();
        
        if (!isset($location_raws[$id])) {
            $this->redirectError('Nieprawidłowy materiał', 'user/location/objects');
        }
        
        $this->view->res = $location_raws[$id];
        $this->view->character = $this->template->character;
        $this->view->max_amount = 
                ($location_raws[$id]['amount'] + $this->template->character['eq_weight'] <= Model_Character::MAX_WEIGHT) 
                ? $location_raws[$id]['amount'] 
                : (Model_Character::MAX_WEIGHT - $this->template->character['eq_weight']);
        
    }

    public function action_give_raw() {
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            $character_id = $this->request->post('character_id');
            $id = $this->request->post('res_id');
            $amount = $this->request->post('amount');

            try {
                $this->character->give_raw_to_character($character_id, $id, $amount);

                //wysłanie eventu
                $event = new Model_Event();
                $event->type = Model_Event::GIVE_RAW;
                $event->date = $this->game->getRawTime();

                $event->add('params', array('name' => 'sndr', 'value' => $this->character->id));
                $event->add('params', array('name' => 'rcpt', 'value' => $character_id));
                $event->add('params', array('name' => 'res_id', 'value' => $id));
                $event->add('params', array('name' => 'amount', 'value' => $amount));

                $event->save();

                $event->notify($this->location->getHearableCharacters());

                $this->redirect('events');
                
            } catch (Exception $e) {
                $this->redirectError($e->getMessage(), 'user/inventory');
            }
            
        }
        
        $id = $this->request->param('id');

        $raws = $this->character->getRaws();
        
        $this->view->characters = $this->location->get_hearable_characters_names($this->character);
        $this->view->res = $raws[$id];
        $this->view->character = $this->template->character;
        $this->view->max_amount = $raws[$id]['amount'];
        
    }

    public function action_use_raw() {
     
        $this->view->resource = new Model_Resource($this->request->param('id'));
        $manager = Model_ProjectManager::getInstance(null, $this->redis);
        
        if (HTTP_Request::POST == $this->request->method()) {
        
            $project = $manager->findOneById($this->request->post('project_id'), TRUE);
            $resource = new Model_Resource($this->request->param('id'));
                
            if ($this->request->post('submit_project')) {
                //project is selected, now show form to set amount
                
                $specs = $project->getAllSpecs();
                $needed_amount = 0;
                
                foreach ($specs as $spec) {
                    if ($spec['resource_id'] == $this->request->param('id')) {
                        $needed_amount = $spec['needed'] - $spec['added'];
                    }
                }
                
                $got_amount = $this->character->getRawAmount($resource->id);
                
                $this->template->content = View::factory('events/use_raw_add')
                    ->bind('needed_amount', $needed_amount)
                    ->bind('got_amount', $got_amount)
                    ->bind('resource', $resource)
                    ->bind('project_id', $project->id);
                return;
                
            } elseif ($this->request->post('submit_raw')) {
                
                $amount = $this->request->post('amount');
                $resource_id = $this->request->post('resource_id');

                try {
                    $this->character->add_raw_to_project($project, $resource_id, $amount);

                    //wysłanie eventu
                    $event = new Model_Event();
                    $event->type = Model_Event::USE_RAW;
                    $event->date = $this->game->getRawTime();

                    $event->add('params', array('name' => 'sndr', 'value' => $this->character->id));
                    $event->add('params', array('name' => 'project_id', 'value' => $project->id));
                    $event->add('params', array('name' => 'project_name', 'value' => $project->getName()));
                    $event->add('params', array('name' => 'res_id', 'value' => $resource_id));
                    $event->add('params', array('name' => 'amount', 'value' => $amount));

                    $event->save();

                    $event->notify($this->location->getVisibleCharacters());

                    $this->redirect('events');
                } catch (Exception $e) {
                    $this->redirectError($e->getMessage(), 'user/inventory');
                }
                
            }
            
        }
        
        $projects_ids = $this->location->getProjectsIds();
        $this->view->projects = array();
        
        foreach ($projects_ids as $project_id) {
            $project = $manager->findOneById($project_id, true);
            if (!$project->hasAllResources()) {
                $this->view->projects[$project_id] = Model_GameTime::formatDateTime($project->created_at, "d-h:m"). ' ' . $project->getName() . ' ('. $this->character->getChname($project->owner_id) . ')';
            }
        }
        
    }
    

}

?>
