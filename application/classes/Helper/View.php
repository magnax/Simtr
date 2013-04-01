<?php

class Helper_View {
    
    public static function LocationName(Model_Building $building, array $character) {
        return HTML::anchor('lname/'.$building->location->id, $building->location->get_lname($character['id']));
    }
    
    public static function CharacterName($id, $name) {
        return sprintf(str_replace('{{base}}', URL::base(), Model_Character::$chname_link), $id, $name);
    }
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
