<?php

class Model_ChNames_Redis extends Model_ChNames {

    public function getName($id_character, $id_character1) {
        $name = $this->source->get("chnames:$id_character:$id_character1");
        if (!$name) {
            $ch = Model_Character::getInstance($this->source, $this)
                ->fetchOne($id_character1);
            if ($ch) {
                if ($id_character!=$id_character1) {
                    $age = $ch->countVisibleAge($this->raw_time);
                    $name = $this->dict->getString("{$ch->getSex()}:$age").' '.$this->dict->getString($ch->getSex());
                } else {
                    $name = $ch->getName();
                }
            }
        }
        return $name ? $name : $this->dict->getString('nieznany');
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