<?php

class Model_ChNames_Redis extends Model_ChNames {

    public function getName($id_character, $id_character1) {
        $name = $this->source->get("characters:$id_character:chnames:$id_character1");
        if (!$name) {
            $ch = Model_Character::getInstance($this->source)
                ->fetchOne($id_character1);
            if ($ch) {
                if ($id_character!=$id_character1) {
                    $age = $ch->countVisibleAge(Model_GameTime::getRawTime());
                    $name = $this->dict->getString("{$ch->getSex()}:$age").' '.$this->dict->getString($ch->getSex());
                } else {
                    $name = $ch->getName();
                }
            } else {
                $name = $this->dict->getString('nieznany');
            }
        }
        return $name;
    }

    function setName($id_character, $id_character1, $new_name) {

        if (!strlen($new_name)) {
            $this->source->del("characters:$id_character:chnames:$id_character1");
        } else {
            $this->source->set("characters:$id_character:chnames:$id_character1", $new_name);
        }

    }
}

?>