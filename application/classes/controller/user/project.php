<?php

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
            $p['owner'] = $this->character->chnames->getName($this->character->getId(), $p['owner_id']);
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
        
        $this->request->redirect('events');
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
        
        $this->request->redirect('events');

    }

    public function action_get_raw($id) {

        $r = Model_Resource::getInstance($this->redis)->
            findOneById($id)->toArray();
        $this->view->resource = $r;
 
    }

    public function action_start() {

        //pierwsze, naiwne liczenie czasu trwania projektu
        //tylko na podstawie ilości i dziennego zbioru
        $res = Model_Resource::getInstance($this->redis)
            ->findOneById($_POST['resource_id']);

        $time = ceil($_POST['amount'] / $res->getGatherBase() * Model_GameTime::DAY_LENGTH);

        $project = Model_ProjectManager::getInstance(
            Model_Project::getInstance(Model_Project::TYPE_GET_RAW, $this->redis)//;
        );

        $data = array(
            'name'=>$res->getType().' '.$res->getName('d'),
            'owner_id'=>$this->character->getId(),
            'amount'=>$_POST['amount'],
            'time'=>$time,
            'type_id'=>$_POST['type_id'],
            'place_type'=>$this->character->getPlaceType(),
            'place_id'=>$this->character->getPlaceID(),
            'resource_id'=>$_POST['resource_id'],
            'created_at'=>$this->game->getRawTime()
        );
        $project->set($data);
        $project->save();

        $this->location->addProject($project->getId(), true);

        $this->request->redirect('projects');
        
    }

}

?>
