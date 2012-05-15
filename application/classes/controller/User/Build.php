<?php

class Controller_User_Build extends Controller_Base_Character {

    public function action_index($menu = null) {
        $this->view->menu = Model_Buildmenu::getInstance($this->redis, $this->dict)
            ->fetchAll($menu);
        if ($menu) {
            $this->view->current = $menu;
        }
    }

    public function action_form($id) {
        $productionSpec = Model_ProductionSpec::getInstance($this->redis, $this->dict)
            ->findOneById($id)->toArray();

        print_r($productionSpec);

        if (isset($productionSpec['object'])) {
            foreach ($productionSpec['raws'] as &$raw) {
                $res = Model_Resource::getInstance($this->redis)
                    ->findOneById($raw['id'])->toArray();
                $raw['name'] = $this->dict->getString($res['name']);
            }
            $this->view->production = $productionSpec;
        } else {
            $this->redirectError('Brakuje specyfikacji produkcji: '.$id);
        }
    }

    public function action_start($id) {

        $spec = Model_ProductionSpec::getInstance($this->redis, $this->dict)
            ->findOneById($id)
            ->toArray();

        if ($spec) {

            $project = Model_Project::getInstance(Model_Project::TYPE_MAKE, $this->redis);

            $data = array(
                'owner_id'=>$this->character->getId(),
                'amount'=>1,
                'time'=>$spec['time'],
                'type_id'=>'make',
                'place_type'=>$this->character->getPlaceType(),
                'place_id'=>$this->character->getPlaceID(),
                'spec'=>$spec['object']['item'],
                'created_at'=>$this->game->getRawTime()
            );
            $project->set($data);

            $projectManager = Model_ProjectManager::getInstance($project, $this->redis);
            $projectManager->save();

            $this->location->addProject($projectManager->getProject()->getId(), true);

            $this->request->redirect('projects');
        }

    }

}

?>
