<?php

class Model_Resource_Redis extends Model_Resource {

    public function findOneById($id, $as_array = null) {

        $data = json_decode($this->source->get("resources:$id"), true);

        if ($as_array) {
            return $data;
        } else {
            $this->id = $id;
            $this->name = $data['name'];
            $this->type = $data['type'];
            $this->gather_base = $data['gather_base'];
            $this->is_raw = isset($data['is_raw']) && $data['is_raw'];

            return $this;
        }

    }

    public function findAll($name_only = true, $raw = false) {
        
        $resources = $this->source->smembers("global:resources");
        
        $returned = array();
        
        foreach ($resources as $res) {
            $data = json_decode($this->source->get("resources:{$res}"), true);
            if ($raw) {
                if (isset($data['is_raw']) && $data['is_raw']) {
                    $returned[$res] = $name_only ? $data['name'] : $data;
                }
            } else {
                $returned[$res] = $name_only ? $data['name'] : $data;
            }
        }
        
        asort($returned);
        
        return $returned;
        
    }

    public function getDictionaryName($type) {
        $n = $this->source->get("resources:{$this->id}:names:$type");
        if (!$n) {
            $n = $this->name;
        }
        return $n;
    }

    public function save() {
        
        //if id is not set, then set it
        if (!$this->id) {
            $this->id = $this->source->incr("global:IDResource");
            //add to global resources list
            $this->source->sadd("global:resources", $this->id);
        }
        $this->source->set("resources:{$this->id}", json_encode($this->toArray()));
    }
    
}

?>
