<?php

class Controller_User_People extends Controller_Base_Character {

    public function action_index() {

        $characters = $this->location
            ->getAllHearableCharacters(true);

        foreach ($characters as &$ch) {
            $ch['name'] = $this->chnames->getName($this->character->getId(), $ch['id']);
        }

        $this->view->characters = $characters;
    }

}

?>
