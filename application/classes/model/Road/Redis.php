<?php

class Model_Road_Redis extends Model_Road {
    
    public function findOneByID($id, $to_array = null) {
        $r = json_decode($this->source->get("exits:$id"), true);
        if ($to_array) {
            return $r;
        } else {
            $this->id = $r['id'];
            $this->level = $r['level'];
            $this->start_location_id = $r['start'];
            $this->end_location_id = $r['end'];
            $this->distance = $r['distance'];
            $this->direction = $r['direction'];
            
            return $this;
        }
    }


    public function getLevels() {
        return array(
            0=>'path',
            1=>'sand road',
            2=>'paved road',
            3=>'highway',
            4=>'expressway'
        );
    }
    
    public function save() {
        
        if (!$this->id) {
            $this->id = $this->source->incr("global:IDRoad");
        }
        
        $this->source->set("exits:{$this->id}", json_encode($this->toArray()));
        
    }
    
}

?>
