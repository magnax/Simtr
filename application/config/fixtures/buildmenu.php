<?php

$this->redis->del("menu:items");
$this->redis->del("menu:products");

$this->redis->sadd("menu:items", 'tools');$loaded++;
$this->redis->sadd("menu:items", 'weapons');$loaded++;

$this->redis->del("menu:tools:items");
$this->redis->del("menu:tools:products");
$this->redis->del("menu:tools:parent");

$this->redis->sadd("menu:tools:products", 'stick');$loaded++;
$this->redis->sadd("menu:tools:products", 'bone_knife');$loaded++;
$this->redis->set("menu:tools:parent", '');$loaded++;

$this->redis->del("menu:weapons:items");
$this->redis->del("menu:weapons:products");
$this->redis->del("menu:weapons:parent");

$this->redis->sadd("menu:weapons:items", 'bronze');$loaded++;
$this->redis->sadd("menu:weapons:products", 'dagger');$loaded++;
$this->redis->sadd("menu:weapons:products", 'bone_club');$loaded++;
$this->redis->set("menu:weapons:parent", '');$loaded++;

$this->redis->set("menu:bronze:parent", 'weapons');$loaded++;


?>
