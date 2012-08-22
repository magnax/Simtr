<?php

$d = array(
    'towns' => array(
        'desert'=>'desert', 
        'hills'=>'hills',
        'forest'=>'forest',
    ),
    'buildings' => array(
        'brick_bld' => 'brick_bld', 
        'stone_bld' => 'stone_bld', 
        'shack' => 'shack',
    ),
);
$this->redis->set("loc_type", json_encode($d)); $loaded++;

?>
