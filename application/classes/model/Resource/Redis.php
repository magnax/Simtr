<?php

class Model_Resource_Redis extends Model_Resource {

    public function findOneById($id) {

        $data = json_decode($this->source->get("resources:$id"), true);

        $this->id = $id;
        $this->name = $data['name'];
        $this->gather_base = $data['gather_base'];

        return $this;

    }

    public function getName($type) {
        $n = $this->source->get("resources:{$this->id}:names:$type");
        if (!$n) {
            $n = $this->name;
        }
        return $n;
    }

}

?>
