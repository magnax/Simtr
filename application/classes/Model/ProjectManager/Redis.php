<?php

class Model_ProjectManager_Redis extends Model_ProjectManager {

    protected $global_id_key = 'global:IDProject';
    
    public function save() {

        $project = $this->_project->toArray();

        if (!$project['id']) {
            $project['id'] = $this->source->incr($this->global_id_key);
            $this->_project->setId($project['id']);
        }
        $this->source->set("projects:{$project['id']}", json_encode($project));

        //zapisz uczestników i aktualnie pracujących:
        $this->source->set("projects:{$project['id']}:participants",
            json_encode($this->_project->getParticipants()));
        $this->source->set("projects:{$project['id']}:workers",
            json_encode($this->_project->getWorkers()));

        //zapisać aktywność projektu
        if ($this->_project->getActive()) {
            $this->source->set("active_projects:{$project['id']}", 1);
        } else {
            $this->source->del("active_projects:{$project['id']}");
        }

    }

    /**
     * @deprecated
     * 
     * @param type $place_id
     * @return type
     */
    public function find($place_id) {

        $projects = array();

        $p = $this->source->smembers("locations:{$place_id}:projects");

        foreach ($p as $project) {
            $projects[] = $this->findOneById($project)->getProject()->toArray();
        }

        return $projects;

    }

    public function findOneById($id, $return_project = false) {

        try {
            $tmp_proj = $this->source->get("projects:$id");
            if ($tmp_proj) {
                $tmp_proj = json_decode($tmp_proj, true);
                //właściwa klasa:
                $model_name = 'Model_Project_'.$tmp_proj['type_id'];
                $p = new $model_name($tmp_proj['type_id'], $this->source);
                //"hydration":
                foreach ($tmp_proj as $key => $val) {
                    $p->{$key} = $val;
                }

                if ($return_project) {
                    return $p;
                } else {
                    $this->_project = $p;
                    return $this;
                }
            }
            return null;
        } catch (RedisException $e) {
            print_r($id);
        }

    }

    public function addParticipant(Model_Character $character, $time) {

        parent::addParticipant($character, $time);

        $id = $this->_project->getId();
        //dodać do listy aktywnych projektów
        $this->source->set("active_projects:{$id}", 1);
        $this->source->set("projects:{$id}:participants", 
            json_encode($this->_project->getParticipants()));
        $this->source->set("projects:{$id}:workers", 
            json_encode($this->_project->getWorkers()));

        //$this->saveWorkers();
    }

    public function getName() {

        $res = json_decode($this->source->get("resources:{$this->resource_id}"), true);
        return $this->source->get("project_types:{$this->type_id}").': '.$res['name'];

    }
    
}

?>
