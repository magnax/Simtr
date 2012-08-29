<?php

class Model_Project_Redis extends Model_Project {

    

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

}

?>
