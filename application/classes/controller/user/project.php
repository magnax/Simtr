<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Project extends Controller_Base_Character {

    /**
     * lista projektów w danej lokacji
     */

    public function action_index() {

        $this->view->character = $this->template->character;

        $projects = Model_ProjectManager::getInstance(
            null, $this->redis)
                ->find($this->location->id);
        
        $this->view->projects = array();
        
        foreach ($projects as $p) {
            
            $name = ORM::factory('chname')->name($this->character->id, $p['owner_id'])->name;
            if (!$name) {
                if ($p['owner_id'] == $this->character->id) {
                    $name = $this->character->name;
                } else {
                    $name = ORM::factory('character')->getUnknownName($p['owner_id'], $this->lang);
                }
            }
            $name = '<a href="/chname?id='.$p['owner_id'].'">'.$name.'</a>';
            
            $project_name = Model_Project::getInstance($p['type_id'])
                ->name($p, $this->character->id);
            
//            $project_name = $p['name'];
//            if ($p['type_id'] == 'Bury') {
//                $buried_char = new Model_Character($p['character_id']);
//                $buried_name = ORM::factory('chname')->name($this->character->id, $buried_char)->name;
//                if (!$buried_name) {
//                    $buried_name = ORM::factory('character')->getUnknownName($buried_char, $this->lang);
//                }
//                $project_name .= ' '.'<a href="/chname?id='.$buried_char.'">'.$buried_name.'</a>';
//            } 
            
            $this->view->projects[] = array(
                'id' => $p['id'],
                'owner_id' => $p['owner_id'],
                'owner' => $name,
                'name' => $project_name,
                'created_at' => $p['created_at'],
            );

//            $p['name']=$this->dict->getString($p['name']);
        }

        $this->view->character = $this->template->character;
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
                    Session::instance()->set('error', $used_slots);
                    
                    if ($used_slots < $this->location->town->slots) {
                        $manager->addParticipant($this->character, $this->game->raw_time);
                        $manager->save();
                        /**
                         * @todo move this stuff to character model, or to project model
                         */
                        $this->redis->set("characters:{$this->character->id}:current_project", $project->getId());
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

        $id = $this->request->param('id');
        
        $manager = Model_ProjectManager::getInstance(null, $this->redis)
            ->findOneById($id);
        $project = $manager->getProject();

        $manager->removeParticipant($this->character, $this->game->raw_time);
        $manager->save();

        RedisDB::getInstance()->del("characters:{$this->character->id}:current_project");
        
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
        
        $spec = ORM::factory('spec')
            ->where('itemtype_id', '=', $this->request->param('id'))
            ->find();
        
        if ($_POST) {
            
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
        
    }
    
}

?>
