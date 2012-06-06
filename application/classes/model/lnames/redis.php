<?php

class Model_LNames_Redis extends Model_LNames {

    public function getName($id_character, $id_location) {
        
        $name = $this->source->get("lnames:{$id_character}:$id_location");
        
        return $name ? $name : null;

    }

    function setName($id_character, $id_location, $new_name) {

        if (!strlen($new_name)) {
            $this->source->del("lnames:{$id_character}:$id_location");
        } else {
            $this->source->set("lnames:{$id_character}:$id_location", $new_name);
        }

    }

}

?>
