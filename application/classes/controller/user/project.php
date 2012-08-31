<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Project extends Controller_Base_Character {

    /**
     * lista projektów w danej lokacji
     */

    public function action_index() {

        $this->view->character = $this->template->character;

        $this->view->projects = Model_ProjectManager::getInstance(
            null, $this->redis)
                ->find($this->character->getPlaceType(), $this->character->getPlaceId());

        foreach ($this->view->projects as &$p) {
            $p['owner'] = $this->character->getChname($this->character->getId(), $p['owner_id']);
            if (!$p['owner'] && $p['owner_id'] == $this->character->getId()) {
                $p['owner'] = $this->character->getName();
            }
            $p['name']=$this->dict->getString($p['name']);
        }
        //print_r($projects);
    }

    public function action_info($id) {
        
        $manager = Model_ProjectManager::getInstance(null, $this->redis)
            ->findOneById($id);
        $this->view->project = $manager->getProject()->toArray();

        print_r($this->view->project);
    }

    public function action_join($id) {

        //manager
        $manager = Model_ProjectManager::getInstance(null, $this->redis)
            ->findOneById($id);
        $project = $manager->getProject();

        $errors = $project->getProjectRequirements();

        if (!$errors) {

            $errors = $project->getUserRequirements($this->character);

            if (!$errors) {

                $manager->addParticipant($this->character, $this->game->getRawTime());
                $manager->save();
                //print_r($manager);

                $this->character->setProjectId($project->getId());
                $this->character->save();

                if ($project->getTypeId() == 'GetRaw') {
                    $this->location->calculateUsedSlots();
                    $this->location->save();
                }
            }
        }

        if ($errors) {
            $session->set_flash('errors', json_encode($errors));
        }
        
        $this->request->redirect('user/event');
    }

    public function action_leave($id) {

        $manager = Model_ProjectManager::getInstance(null, $this->redis)
            ->findOneById($id);
        $project = $manager->getProject();

        $manager->removeParticipant($this->character, $this->game->getRawTime());
        $manager->save();

        $this->character->setProjectId(null);
        $this->character->save();

        if ($project->getTypeId() == 'GetRaw') {
            $this->location->calculateUsedSlots();
            $this->location->save();
        }
        
        $this->request->redirect('user/event');

    }

    public function action_get_raw() {
        
        if ($_POST) {
            
            //pierwsze, naiwne liczenie czasu trwania projektu
            //tylko na podstawie ilości i dziennego zbioru
            $res = ORM::factory('resource', $_POST['resource_id']);
            
            $time = ceil($_POST['amount'] / $res->gather_base * Model_GameTime::DAY_LENGTH);
//
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
