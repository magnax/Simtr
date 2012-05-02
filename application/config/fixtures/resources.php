<?php

$data[3] = array(
  'id' => 3,
  'type'=>'dig',
  'name' => 'ziemniaki',
  'gather_base' => 1600
);

$data[7] = array(
  'id' => 7,
  'type'=>'farm',
  'name' => 'pomidory',
  'gather_base' => 420
);

$data[11] = array(
  'id' => 11,
  'type'=>'dig',
  'name' => 'piasek',
  'gather_base' => 2000
);

$data[12] = array(
  'id' => 12,
  'type'=>'grab',
  'name' => 'drewno',
  'gather_base' => 300
);

$this->redis->set("resources:3:names:d", 'ziemniaków');$loaded++;
$this->redis->set("resources:7:names:d", 'pomidorów');$loaded++;
$this->redis->set("resources:11:names:d", 'piasku');$loaded++;
$this->redis->set("resources:12:names:d", 'drewna');$loaded++;

?>
