<?php

/**
 * @todo rozszerzyć o więcej atrybutów przedmiotów
 * klasa
 * atak
 * obrona
 * waga
 * punkty
 * zuzycie
 * naprawa
 * widoczność 
 */

$data[0] = array(  //"pusty" item
    'name' => '(none)',
    'shield' => 0,
    'points' => 0,
    'weight' => 0,
    'visible' => false,
    'rot' => 0,
    'rot_use' => 0,
    'repair' => 0,
);
    
$data[1] = array(
    'name' => 'bare_fist',
    'attack' => 10,
    'shield' => null,
    'points' => 0,
    'weight' => 0,
    'visible' => false,
    'rot' => 0,
    'rot_use' => 0,
    'repair' => 0,
);

$data[2] = array(
    'name' => 'bone_knife',
    'attack' => 20,
    'shield' => null,
    'points' => 1000,
    'weight' => 100,
    'visible' => false,
    'rot' => 11,
    'rot_use' =>55,
    'repair' => 400,
);

$data[3] = array(
    'name' => 'small_bone_shield',
    'attack' => null,
    'shield' => 16,
    'points' => 1500,
    'weight' => 530,
    'visible' => true,
    'rot' => 11,
    'rot_use' =>53,
    'repair' => 300,
);
    

?>
