<?php

class Model_Project_Redis extends Model_Project {

    private $places = array(
        'loc'=>'locations',
        'shp'=>'ships',
        'cab'=>'cabins',
        'veh'=>'vehicles',
        'bld'=>'buildings',
        'rom'=>'rooms'
    );

    public function set(array $data) {

        $new_id = $this->source->incr($this->global_id_key);

        $this->id = $new_id;
        $this->owner_id = $data['owner_id'];
        $this->type_id = $data['type_id'];
        $this->place_type = $data['place_type'];
        $this->place_id = $data['place_id'];
        $this->amount = $data['amount'];
        $this->time = $data['time'];
        $this->resource_id = $data['resource_id'];
        $this->time_elapsed = 0;
        $this->created_at = $data['created_at'];

        $this->source->set("projects:{$this->id}", json_encode($this->toArray()));
        $this->source->sadd("{$this->places[$this->place_type]}:$this->place_id:projects", $this->id);
        
    }

    public function find($place_type, $place_id) {

        $projects = array();
        $key = $this->places[$place_type];
        $p = $this->source->smembers("$key:{$place_id}:projects");

        foreach ($p as $project) {
            $projects[] = $this->findOneById($project)->toArray();
        }

        return $projects;

    }

    public function findOneById($id) {

        $p = new Model_Project_Redis($this->source);
        $tmp_proj = $this->source->get("projects:$id");
        if ($tmp_proj) {
            $tmp_proj = json_decode($tmp_proj, true);
            //"hydration":
            foreach ($tmp_proj as $key => $val) {
                $p->{$key} = $val;
            }
            $p->participants = json_decode($this->source->get("projects:$id:participants"), true);
            return $p;
        }
        return null;

    }

    protected function saveWorkers() {

        $this->source->del("projects:{$this->id}:workers");
        foreach ($this->workers as $w) {
            $this->source->sadd("projects:{$this->id}:workers", $w);
        }

    }

    public function addParticipant(Model_Character $character, $time) {

        $character->setProjectId($this->id);

        //dodać do listy aktywnych projektów
        $this->source->set("active_projects:{$this->id}", 1);

        $new_participant = array(
            'id'=>$character->getId(),
            'start'=>$time,
            'end'=>null,
            'factor'=>1 //na razie, w przyszłości będzie różny
        );

        $this->participants[] = $new_participant;
        $this->source->set("projects:{$this->id}:participants", json_encode($this->participants));
        $this->workers[] = $character->getId();
        $this->saveWorkers();

    }

    public function removeParticipant(Model_Character $character, $time) {

        $character->setProjectId(null);

        $active_participants = false;

        $tmp_part = array();

        foreach ($this->participants as $participant) {
            if ($participant['id'] == $character->getId() && !$participant['end']) {
                $participant['end'] = $time;
            }
            if (!$participant['end']) {
                $active_participants = true;
            }
            $tmp_part[] = $participant;
        }

        $this->participants = $tmp_part;
        
        if (!$active_participants) {
            //usunąć z listy aktywnych projektów
            $this->source->del("active_projects:{$this->id}");
        }

        $this->source->set("projects:{$this->id}:participants", json_encode($this->participants));
        foreach ($this->workers as &$w) {
            if ($w == $character->getId()) {
                unset($w);
            }
        }
        $this->saveWorkers();

    }

    public function getName() {

        $res = json_decode($this->source->get("resources:{$this->resource_id}"), true);
        return $this->source->get("project_types:{$this->type_id}").': '.$res['name'];

    }

}

?>
