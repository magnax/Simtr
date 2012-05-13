<?php

class Model_LNames_Redis extends Model_LNames {

    public function getName($id_location) {
        
        if ($this->character_id) {
            $name = $this->source->get("characters:{$this->character_id}:lnames:$id_location");
        } else {
            $name = null;
        }
        
        if (!$name) {
            $name = $this->dict->getString('Nienazwane miejsce');
        }
        
        return $name;
    }

    function setName($id_location, $new_name) {

        if (!strlen($new_name)) {
            $this->source->del("characters:{$this->character_id}:lnames:$id_location");
        } else {
            $this->source->set("characters:{$this->character_id}:lnames:$id_location", $new_name);
        }

    }

}

?>
