<?php

class Helper_View {
    
    public static function LocationName(Model_Building $building, array $character) {
        return HTML::anchor('lname/'.$building->location->id, $building->location->get_lname($character['id']));
    }
    
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
