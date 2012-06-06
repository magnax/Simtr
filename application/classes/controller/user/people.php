<?php

class Controller_User_People extends Controller_Base_Character {

    public function action_index() {

        $characters = $this->location
            ->getAllHearableCharacters(true, $this->character);

        foreach ($characters as &$ch) {
            $name = $this->character->getChname($ch['id']);
            if (!$name) {
                $name = $this->character->getUnknownName($ch['id']);
                $name = Model_Dict::getInstance($this->redis)->getString($name);
            }
            $ch['name'] = $name;
        }

        $this->view->characters = $characters;
    }

}

?>
