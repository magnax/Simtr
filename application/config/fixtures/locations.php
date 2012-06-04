<?php

/**
 * data for sample locations
 */
$data[1] = array(
    'id'=>1,
    'x'=>0,
    'y'=>0,
    'name'=>'Simtr Forest',
    'type'=>'forest',
    'resources'=>array(3,7,11,12),
    'res_slots'=>6,
    'used_slots'=>0
);
$this->redis->sadd("global:locations", 1);$loaded++;

$data[2] = array(
    'id'=>2,
    'x'=>50,
    'y'=>40,
    'name'=>'Simtr Grassland',
    'type'=>'grass',
    'resources'=>array(3,7,11),
    'res_slots'=>8,
    'used_slots'=>0
);
$this->redis->sadd("global:locations", 2);$loaded++;

// Locations type:
$this->redis->sadd("global:loc_type", 'grass');$loaded++;
$this->redis->sadd("global:loc_type", 'forest');$loaded++;
$this->redis->sadd("global:loc_type", 'desert');$loaded++;
$this->redis->sadd("global:loc_type", 'hills');$loaded++;
$this->redis->sadd("global:loc_type", 'mountains');$loaded++;

?>
