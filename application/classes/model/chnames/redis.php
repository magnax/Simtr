<?php

class Model_ChNames_Redis extends Model_ChNames {

    //get name of character1 memorized by character (or null)
    public function getName($id_character, $id_character1) {
        
        $name = $this->source->get("chnames:$id_character:$id_character1");
        return $name ? $name : null;
        
    }

    
    function setName($id_character, $id_character1, $new_name) {

        if (!strlen($new_name)) {
            $this->source->del("chnames:$id_character:$id_character1");
        } else {
            $this->source->set("chnames:$id_character:$id_character1", $new_name);
        }

    }
}

?>