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
            
            $this->view->projects[] = array(
                'id' => $p['id'],
                'owner_id' => $p['owner_id'],
                'owner' => $name,
                'name' => $p['name'],
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
        $this->view->project = $manager->getProject()->toArray();

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

                    }

                    $this->redis->set("characters:{$this->character->id}:current_project", $project->getId());

                }
            }
        }

        if ($errors) {
            $this->session->set_flash('errors', json_encode($errors));
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
                'name'=>$res->projecttype->find()->name.' '.$res->d,
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

}

?>
