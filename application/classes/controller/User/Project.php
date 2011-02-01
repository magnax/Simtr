<?php

class Controller_User_Project extends Controller_Base_Character {

    /**
     * lista projektów w danej lokacji
     */

    public function action_index() {

        $this->view->character = $this->template->character;

        $this->view->projects = Model_Project::getInstance($this->redis)
            ->find($this->character->getPlaceType(), $this->character->getPlaceId());

        foreach ($this->view->projects as &$p) {
            $p['owner'] = $this->chnames->getName($this->character->getId(), $p['owner_id']);
            if (!$p['owner'] && $p['owner_id'] == $this->character->getId()) {
                $p['owner'] = $this->character->getName();
            }
            $p['name'] = Model_Project::getInstance($this->redis)
                    ->findOneById($p['id'])
                    ->getName();
        }
        //print_r($projects);
    }

    public function action_info($id) {
        $this->view->project = Model_Project::getInstance($this->redis)
            ->findOneById($id)
            ->toArray();
    }

    public function action_join($id) {
        $project = Model_Project::getInstance($this->redis)
                ->findOneById($id);
        $project->addParticipant($this->character, $this->game->getRawTime());
        $this->character->save();

        if ($project->getTypeId() == 'get_raw') {
            $this->location->calculateUsedSlots();
            $this->location->save();
        }

        $this->request->redirect('events');
    }

    public function action_leave($id) {

        $project = Model_Project::getInstance($this->redis)
            ->findOneById($id);
        $project->removeParticipant($this->character, $this->game->getRawTime());
        $this->character->save();

        if ($project->getTypeId() == 'get_raw') {
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

        $project = Model_Project::getInstance($this->redis);

        $data = array(
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

        $this->request->redirect('projects');
        
    }

}

?>
