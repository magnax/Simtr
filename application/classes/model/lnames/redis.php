<?php

class Model_LNames_Redis extends Model_LNames {

    public function getName($id_location) {
        
        if ($this->character_id) {
            $name = $this->source->get("lnames:{$this->character_id}:$id_location");
        } else {
            $name = null;
        }
        
        if (!$name) {
            $name = $this->dict->getString('unnamed_location');
        }
        
        return $name;
    }

    function setName($id_location, $new_name) {

        if (!strlen($new_name)) {
            $this->source->del("lnames:{$this->character_id}:$id_location");
        } else {
            $this->source->set("lnames:{$this->character_id}:$id_location", $new_name);
        }

    }

}

?>
