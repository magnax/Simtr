<?php defined('SYSPATH') or die('No direct script access.');

/**
 * controller for projects:
 * - list of projects in location
 * - join/leave project
 * - create projects: MAKE, GET_RAW
 */
class Controller_User_Project extends Controller_Base_Character {

    /**
     * lists all projects in current location
     */
    public function action_index() {

        $projects = $this->location->getProjectsIds();
        
        $this->view->projects = array();

        foreach ($projects as $project_id) {
            
            $project = Model_Project::factory(null, $project_id);
            $project_name = $project->get_name();
            
            $workers = $project->get_workers();
            
            $progress = $project->calculateProgress();
            
            $this->view->projects[] = array(
                'id' => $project->id,
                'owner_id' => $project->owner_id,
                'owner_name' => $this->character->getChname($project->owner_id),
                'name' => $project_name,
                'created_at' => $project->created_at,
                'progress' => $progress,
                'running' => !!$workers,
                'workers' => count($workers),
                'can_join' => !(isset($progress) && $progress == '-'),
                'can_delete' => !$workers && (($project->owner_id == $this->character->id) || 
                        ($project->created_at - $this->game->raw_time >= 20*Model_GameTime::DAY_LENGTH)),
            );

        }

    }

    public function action_info() {
        
        $project = Model_Project::factory(null, $this->request->param('id'));
        
        if (!$project->loaded()) {
            $this->redirectError('Nieprawidłowy projekt', 'user/project');
        }
        
        $project_array = $project->as_array();
        
        $characters = $this->location->getHearableCharacters();
        if (in_array($project->owner_id, $characters)) {
            $owner = ORM::factory('User', $project->owner_id);
            $name = $this->character->getChname($project->owner_id);
            $project_array['owner'] = '<a href="/chname/'.$project->owner_id.'">'.$name.'</a>';
        } else {
            $project_array['owner'] = 'Już go tu nie ma';
        }
        
        //get all workers
        $workers = $project->get_workers();
        $project_array['workers'] = array();
        
        if (is_array($workers)) {
            foreach ($workers as $worker) {
                $name = $this->character->getChname($worker);
                $project_array['workers'][] = array(
                    'id'=>$worker,
                    'name'=>$name
                );
            }
        }
        
        $project_array['name'] = $project->get_name();
        $project_array['date'] = Model_GameTime::formatDateTime($project->created_at, 'd-h');
        $project_array['progress'] = number_format((100 * $project->time_elapsed / $project->time), 2).' procent';
        $project_array['materials'] = array();
        if ($project->time < 86400) {
            $project_array['time'] = gmdate('H:i:s', $project->time);
        } else {
            $project_array['time'] = gmdate('d H:i', $project->time);
        }
        //print_r($project);
        $this->view->project = $project_array;

        $this->view->project_specs = $project->getAllSpecs();
        $this->view->can_join = $project->hasAllSpecs();
        
    }

    public function action_join() {

        $project = Model_Project::factory(null, $this->request->param('id'));
        
        if (!$project->loaded()) {
            $this->redirectError('Nieprawidłowy projekt', 'user/project');
        }

        $errors = $project->getProjectRequirements();

        if (!$errors) {

            $errors = $project->getUserRequirements($this->character);

            if (!$errors) {

                if ($project->type_id == 'GetRaw') {
                    $used_slots = $this->location->countUsedSlots($this->redis);
                    
                    if ($used_slots < $this->location->town->slots) {
//                        $manager->addParticipant($this->character, $this->game->raw_time);
//                        $manager->save();
                        /**
                         * @todo move this stuff to character model, or to project model
                         */
//                        $this->redis->set("characters:{$this->character->id}:current_project", $project->getId());
                    } else {
                        Session::instance()->set('error', $used_slots);
                    }  

                } else {
//                    $manager->addParticipant($this->character, $this->game->raw_time);                 
//                    $manager->save();
//                    $this->redis->set("characters:{$this->character->id}:current_project", $project->getId());
                }
                
                $project->addParticipant($this->character, $this->game->raw_time);
                
            }
        }

        if ($errors) {
            Session::instance()->set('errors', json_encode($errors));
        }
        
        $this->redirect('events');
    }

    public function action_leave() {

        $this->character->leaveCurrentProject($this->redis, $this->game->raw_time);
        
        $this->redirect('events');

    }

    public function action_destroy() {
        
        $project_id = $this->request->param('id');
        
        $project = $this->manager->findOneById($project_id, true);
        if (!$project->id || $project->owner_id != $this->character->id) {
            Session::instance()->set('error', 'Bad project!');
        } else {
        
            RedisDB::del("projects:{$project->id}");
            RedisDB::srem("locations:{$this->location->id}:projects", $project->id);
        }
        $this->redirect('events');
        
    }


    public function action_get_raw() {
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            //pierwsze, naiwne liczenie czasu trwania projektu
            //tylko na podstawie ilości i dziennego zbioru
            $res = ORM::factory('Resource', $_POST['resource_id']);
            
            $time = ceil($_POST['amount'] / $res->gather_base * Model_GameTime::DAY_LENGTH);

            $project = Model_Project::factory(Model_Project::TYPE_GET_RAW);

            $data = array(
                'name'=>$res->projecttype->name.' '.$res->d,
                'owner_id'=>$this->character->id,
                'amount'=>$_POST['amount'],
                'time'=>$time,
                'type_id'=>$_POST['type_id'],
                'location_id'=>$this->location->id,
                'resource_id'=>$_POST['resource_id'],
                'created_at'=>$this->game->getRawTime()
            );
            //print_r($project);
            $project->values($data);
            $project->save();

            $this->location->add_project($project->id);

            $this->redirect('events');
        }
        
        $id = $this->request->param('id');
        $r = new Model_Resource($id);
        $this->view->resource = $r->as_array();
 
    }

    public function action_builditem() {
        
        $itemtype = ORM::factory('ItemType', $this->request->param('id'));
        $project_type = ORM::factory('ProjectType', $itemtype->projecttype_id);
        
        $spec = ORM::factory('Spec')
            ->where('itemtype_id', '=', $this->request->param('id'))
            ->find();
        
        $raws = Model_Spec_Raw::getRaws($itemtype->id);
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            $project_manager = Model_ProjectManager::getInstance(
                Model_Project::getInstance($project_type->key, $this->redis)//;
            );

            $data = array(
                'name'=>'Produkcja: '.$spec->item->name,
                'owner_id'=>$this->character->id,
                'amount'=>1,
                'time'=>$spec->time,
                'type_id'=>$project_type->key,
                'place_type'=>$this->location->locationtype_id,
                'place_id'=>$this->location->id,
                'itemtype_id'=>$spec->itemtype_id,
                'created_at'=>$this->game->getRawTime()
            );

            $project_manager->set($data);
            $project_manager->save();

            $this->location->addProject($project_manager->getId(), $this->redis);
            
            foreach ($raws as $raw) {
                
                $project_raw = new Model_Project_Raw();
                $project_raw->project_id = $project_manager->getId();
                $project_raw->resource_id = $raw->resource_id;
                $project_raw->amount = 0;
                
                $project_raw->save();
                
            }

            $this->redirect('events');
        }
        
        $this->view->spec = $spec;
        $this->view->raws = $raws;
        
    }
    
}

?>
