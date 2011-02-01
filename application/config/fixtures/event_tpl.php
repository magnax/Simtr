<?php

//GetRaw
$key = "global:event_tpl:GetRaw";
$person = 1;
$this->redis->set("$key:$person", 'Bierzesz %s gram %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'res_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'amount');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s bierze trochę %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'res_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'sndr');$loaded++;

//GetRawEnd - surowiec do plecaka
$key = "global:event_tpl:GetRawEnd";
$person = 1;
$this->redis->set("$key:$person", 'Projekt %s %s zakończony, %s wyprodukowane i trafia do Twojego ekwipunku');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'amount');$loaded++;
$this->redis->lpush("$key:$person:params", 'res_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'name');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Projekt %s %s zakończony, %s wyprodukowane trafia do ekwipunku %s');$loaded++;
$this->redis->del("$key:$person:params");

$this->redis->lpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->lpush("$key:$person:params", 'amount');$loaded++;
$this->redis->lpush("$key:$person:params", 'res_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'name');$loaded++;

//GetRawEndGround - surowiec na ziemię
$key = "global:event_tpl:GetRawEndGround";
$person = 1;
$this->redis->set("$key:$person", 'Projekt %s %s zakończony, %s wyprodukowane trafia na ziemię');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'name');$loaded++;
$this->redis->lpush("$key:$person:params", 'res_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'amount');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Projekt %s %s zakończony, %s wyprodukowane trafia na ziemię');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'name');$loaded++;
$this->redis->lpush("$key:$person:params", 'res_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'amount');$loaded++;

?>
