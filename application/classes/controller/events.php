<?php defined('SYSPATH') or die('No direct script access.');

/**
 * kontroler listy zdarzeń
 */
class Controller_Events extends Controller_Base_Character {
    
    public function action_index() {
        
        $page = $this->request->param('page', 1);

        //$events = Model_Character_Redis::getEvents($this->character->id, $this->redis, $this->lang, $page);
        $events = $this->character->getEvents($page);
        $this->view->first_new_event = $this->redis->lpop("new_events:{$this->character->id}");
        $this->redis->del("new_events:{$this->character->id}");
        $this->view->events = $events;

    }

    public function action_events2($page = 1) {
        $this->template->set_filename('templates/new_character');
        $events = Model_Character_Redis::getEvents($this->character->id, $this->redis, $this->lang, $page);
        $this->template->user_token = 'abc';
    }

    public function action_talkall() {
        
        if (isset($_POST['text']) && $_POST['text']) {
            
            $event_sender = Model_EventSender::getInstance(
                Model_Event::getInstance(
                    Model_Event::TALK_ALL, $this->game->raw_time, $this->redis
                )
            );

            $event_sender->setText($_POST['text']);
            
            //recipients to lista obiektów klasy Character
            $recipients = $this->location->getHearableCharacters($this->character);
            $event_sender->addRecipients($recipients);
            $event_sender->setSender($this->character->id);

            $event_sender->send();
            
            Model_EventNotifier::notify(
                $event_sender->getEvent()->getRecipients(), 
                $event_sender->getEvent()->getId(), 
                $this->redis, $this->lang
            );

        }

        if ($this->request->is_ajax()) {
            $this->auto_render = false;
            echo 'success';
            return;
        }
        
        $this->request->redirect('events');
        
    }

    public function action_put_raw() {
        
        if ($_POST) {
            
            $id = $_POST['res_id'];
            $amount = $_POST['amount'];

            $this->character->putRaw($id, $amount);
            $this->location->addRaw($id, $amount);

            //wysłanie eventu
            $event_sender = Model_EventSender::getInstance(
                Model_Event::getInstance(
                    Model_Event::PUT_RAW, $this->game->getRawTime(), $this->redis
                )
            );
            $event_sender->setResource($_POST['res_id'], $_POST['amount']);
            //recipients to lista obiektów klasy Character
            $event_sender->addRecipients($this->location->getVisibleCharacters());
            $event_sender->setSender($this->character->id);
            $event_sender->send();

            Model_EventNotifier::notify(
                $event_sender->getEvent()->getRecipients(), 
                $event_sender->getEvent()->getId(), 
                $this->redis, $this->lang
            );
            
            $this->request->redirect('events');
            
        }
        
        $id = $this->request->param('id');
        
        $inventory = $this->character->getRaws();
        $this->view->res = $inventory[$id];
        $this->view->character = $this->template->character;
    }

    public function action_get_raw() {
        
        if ($_POST) {
        
            $id = $_POST['res_id'];
            $amount = $_POST['amount'];

            $this->location->putRaw($id, $amount);
            $this->character->addRaw($id, $amount);

            //wysłanie eventu
            $event_sender = Model_EventSender::getInstance(
                Model_Event::getInstance(
                    Model_Event::GET_RAW, $this->game->raw_time, $this->redis
                )
            );
            $event_sender->setResource($_POST['res_id'], $_POST['amount']);
            //recipients to lista obiektów klasy Character
            $event_sender->addRecipients($this->location->getVisibleCharacters());
            $event_sender->setSender($this->character->id);
            $event_sender->send();

            Model_EventNotifier::notify(
                $event_sender->getEvent()->getRecipients(), 
                $event_sender->getEvent()->getId(), 
                $this->redis, $this->lang
            );
            
            $this->request->redirect('events');
            
        }
        
        $id = $this->request->param('id');
        
        $raws = $this->location->getRaws();
        $this->view->res = $raws[$id];
        $this->view->character = $this->template->character;
        
    }

    public function action_give_raw() {

        if ($_POST) {
            
            $dest_character = ORM::factory('character', $_POST['character_id']);
            
            $this->character->putRaw($_POST['res_id'], $_POST['amount']);
            $dest_character->addRaw($_POST['res_id'], $_POST['amount']);

            //wysłanie eventu
            $event_sender = Model_EventSender::getInstance(
                Model_Event::getInstance(
                    Model_Event::GIVE_RAW, $this->game->raw_time, $this->redis
                )
            );
            $event_sender->setResource($_POST['res_id'], $_POST['amount']);
            //recipients to lista obiektów klasy Character
            $event_sender->addRecipients($this->location->getVisibleCharacters());
            $event_sender->setSender($this->character->id);
            $event_sender->setRecipient($dest_character->id);
            $event_sender->send();

            Model_EventNotifier::notify(
                $event_sender->getEvent()->getRecipients(), 
                $event_sender->getEvent()->getId(), 
                $this->redis, $this->lang
            );
            
            $this->request->redirect('events');
        }
        
        $id = $this->request->param('id');
        
        $raws = $this->character->getRaws();
        $all_characters = $this->location->getHearableCharacters();
        $this->view->characters = array();
        foreach ($all_characters as $ch) {
            if ($ch != $this->character->id) {
                $name = ORM::factory('chname')->name($this->character->id, $ch)->name;
                if (!$name) {
                    $name = ORM::factory('character')->getUnknownName($ch, $this->lang);
                }
                $this->view->characters[$ch] = $name;
            }
        }
        $this->view->res = $raws[$id];
        $this->view->character = $this->template->character;
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
                    if ($spec['id'] = $this->request->param('id')) {
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
                
                //dodanie materiału do projektu
                $project->addRaw($_POST['resource_id'], $_POST['amount']);
                
                //odjęcie postaci podanej ilości materiału
                $this->character->putRaw($_POST['resource_id'], $_POST['amount']);
                
                //wysłanie eventu
                $event_sender = Model_EventSender::getInstance(
                    Model_Event::getInstance(
                        Model_Event::USE_RAW, $this->game->raw_time, $this->redis
                    )
                );
                $event_sender->setResource($_POST['resource_id'], $_POST['amount']);
                $event_sender->setProject($project);
                //recipients to lista obiektów klasy Character
                $event_sender->addRecipients($this->location->getVisibleCharacters());
                $event_sender->setSender($this->character->id);
                $event_sender->send();

                Model_EventNotifier::notify(
                    $event_sender->getEvent()->getRecipients(), 
                    $event_sender->getEvent()->getId(), 
                    $this->redis, $this->lang
                );

                $this->request->redirect('events');
                
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
