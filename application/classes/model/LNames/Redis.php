<?php

class Model_LNames_Redis extends Model_LNames {

    public function getName($id_character, $id_location) {
        $name = $this->source->get("characters:$id_character:lnames:$id_location");
        if (!$name) {
            $name = $this->dict->getString('Nienazwane miejsce');
        }
        return $name;
    }

    function setName($id_character, $id_location, $new_name) {

        if (!strlen($new_name)) {
            $this->source->del("characters:$id_character:lnames:$id_location");
        } else {
            $this->source->set("characters:$id_character:lnames:$id_location", $new_name);
        }

    }

}

?>
