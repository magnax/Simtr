<?php defined('SYSPATH') or die('No direct script access.');

/**
 * controller for projects:
 * - list of projects in location
 * - join/leave project
 * - create projects: MAKE, GET_RAW
 */
class Controller_User_Project extends Controller_Base_Character {
    
    /**
     * project manager object - manages project get/save
     */
    protected $manager = null;
    
    public function before() {
        
        parent::before();
        
        //create manager
        $this->manager = Model_ProjectManager::getInstance(null, $this->redis);
    }

    /**
     * lists all projects in current location
     */
    public function action_index() {

        $projects = $this->location->getProjectsIds();
        
        $this->view->projects = array();
        
        foreach ($projects as $project_id) {
            
            $project = $this->manager->findOneById($project_id, true);
            
            $name = $this->character->getChname($project->owner_id);
            $name = '<a href="/chname?id='.$project->owner_id.'">'.$name.'</a>';
            
            $project_name = $project->getName();
            
            $workers = $this->manager->getWorkersIds($project_id);
            
            if ($project->type_id == 'Make') {    
                if (!$project->hasAllSpecs()) {
                    $progress = '-';
                }
            }
            
            $this->view->projects[] = array(
                'id' => $project->id,
                'owner_id' => $project->owner_id,
                'owner' => $name,
                'name' => $project_name,
                'created_at' => $project->created_at,
                'progress' => (isset($progress)) ? $progress : $project->calculateProgress(),
                'running' => !!$workers,
                'workers' => count($workers),
                'can_join' => !(isset($progress) && $progress == '-'),
            );

        }

    }

    public function action_info() {
        
        $id = $this->request->param('id');
        
        $manager = Model_ProjectManager::getInstance(null, $this->redis)
            ->findOneById($id);
        $project = $manager->getProject()->toArray();
        
        $characters = $this->location->getHearableCharacters();
        if (in_array($project['owner_id'], $characters)) {
            $owner = ORM::factory('user', $project['owner_id']);
            $name = ORM::factory('chname')->name($this->character->id, $project['owner_id'])->name;
            if (!$name) {
                if ($project['owner_id'] == $this->character->id) {
                    $name = $this->character->name;
                } else {
                    $name = ORM::factory('character')->getUnknownName($project['owner_id'], $this->lang);
                }
            }
            $project['owner'] = '<a href="/chname?id='.$project['owner_id'].'">'.$name.'</a>';
        } else {
            $project['owner'] = 'Już go tu nie ma';
        }
        
        //get all workers
        $workers = json_decode($this->redis->get("projects:{$project['id']}:workers"), true);
        $project['workers'] = array();
        
        if (is_array($workers)) {
            foreach ($workers as $worker) {
                $name = ORM::factory('chname')->name($this->character->id, $worker)->name;
                if (!$name) {
                    if ($worker == $this->character->id) {
                        $name = $this->character->name;
                    } else {
                        $name = ORM::factory('character')->getUnknownName($worker, $this->lang);
                    }
                }
                $project['workers'][] = array(
                    'id'=>$worker,
                    'name'=>$name
                );
            }
        }
        
        $project['name'] = Model_Project::getInstance($project['type_id'])
            ->name($project, $this->character->id);
        $project['date'] = Model_GameTime::formatDateTime($project['created_at'], 'd-h');
        $project['progress'] = number_format((100 * $project['time_elapsed'] / $project['time']), 2).' procent';
        $project['materials'] = array();
        if ($project['time'] < 86400) {
            $project['time'] = gmdate('H:i:s', $project['time']);
        } else {
            $project['time'] = gmdate('d H:i', $project['time']);
        }
        //print_r($project);
        $this->view->project = $project;

        $project_for_specs = Model_ProjectManager::getInstance(null, $this->redis)->findOneById($project['id'], true);
        $this->view->project_specs = $project_for_specs->getAllSpecs();
        $this->view->can_join = $project_for_specs->hasAllSpecs();
        
    }

    public function action_join() {

        $id = $this->request->param('id');
        //manager
        $manager = Model_ProjectManager::getInstance(null, $this->redis)
            ->findOneById($id);
        $project = $manager->getProject();

        $errors = $project->getProjectRequirements();

        if (!$errors) {

            $errors = $project->getUserRequirements($this->character);

            if (!$errors) {

                if ($project->getTypeId() == 'GetRaw') {
                    $used_slots = $this->location->countUsedSlots($this->redis);
                    
                    if ($used_slots < $this->location->town->slots) {
                        $manager->addParticipant($this->character, $this->game->raw_time);
                        $manager->save();
                        /**
                         * @todo move this stuff to character model, or to project model
                         */
                        $this->redis->set("characters:{$this->character->id}:current_project", $project->getId());
                    } else {
                        Session::instance()->set('error', $used_slots);
                    }  

                } else {
                    $manager->addParticipant($this->character, $this->game->raw_time);                 
                    $manager->save();
                    $this->redis->set("characters:{$this->character->id}:current_project", $project->getId());
                }
            }
        }

        if ($errors) {
            Session::instance()->set('errors', json_encode($errors));
        }
        
        $this->request->redirect('events');
    }

    public function action_leave() {

        $this->character->leaveCurrentProject($this->redis, $this->game->raw_time);
        
        $this->request->redirect('events');

    }

    public function action_get_raw() {
        
        if ($_POST) {
            
            //pierwsze, naiwne liczenie czasu trwania projektu
            //tylko na podstawie ilości i dziennego zbioru
            $res = ORM::factory('resource', $_POST['resource_id']);
            
            $time = ceil($_POST['amount'] / $res->gather_base * Model_GameTime::DAY_LENGTH);

            $project_manager = Model_ProjectManager::getInstance(
                Model_Project::getInstance(Model_Project::TYPE_GET_RAW, $this->redis)//;
            );

            $data = array(
                'name'=>$res->projecttype->name.' '.$res->d,
                'owner_id'=>$this->character->id,
                'amount'=>$_POST['amount'],
                'time'=>$time,
                'type_id'=>$_POST['type_id'],
                'place_type'=>$this->location->locationtype_id,
                'place_id'=>$this->location->id,
                'resource_id'=>$_POST['resource_id'],
                'created_at'=>$this->game->getRawTime()
            );

            $project_manager->set($data);
            $project_manager->save();

            $this->location->addProject($project_manager->getId(), $this->redis);

            $this->request->redirect('events');
        }
        
        $id = $this->request->param('id');
        $r = new Model_Resource($id);
        $this->view->resource = $r->as_array();
 
    }

    public function action_builditem() {
        
        $itemtype = ORM::factory('itemtype', $this->request->param('id'));
        $project_type = ORM::factory('projecttype', $itemtype->projecttype_id);
        
        $spec = ORM::factory('spec')
            ->where('itemtype_id', '=', $this->request->param('id'))
            ->find();
        
        $raws = Model_Spec_Raw::getRaws($itemtype->id);
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            $project_manager = Model_ProjectManager::getInstance(
                Model_Project::getInstance(Model_Project::TYPE_MAKE, $this->redis)//;
            );

            $data = array(
                'name'=>'Produkcja: '.$spec->item->name,
                'owner_id'=>$this->character->id,
                'amount'=>1,
                'time'=>$spec->time,
                'type_id'=>Model_Project::TYPE_MAKE,
                'place_type'=>$this->location->locationtype_id,
                'place_id'=>$this->location->id,
                'itemtype_id'=>$spec->itemtype_id,
                'created_at'=>$this->game->getRawTime()
            );

            $project_manager->set($data);
            $project_manager->save();

            $this->location->addProject($project_manager->getId(), $this->redis);

            $this->request->redirect('events');
        }
        
        $this->view->spec = $spec;
        $this->view->raws = $raws;
        
    }
    
}

?>
