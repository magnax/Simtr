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

            return $this;
        }

    }

    public function getDictionaryName($type) {
        $n = $this->source->get("resources:{$this->id}:names:$type");
        if (!$n) {
            $n = $this->name;
        }
        return $n;
    }

    public function save() {
        $this->source->set("resources:{$this->id}", json_encode($this->toArray()));
    }
    
}

?>
