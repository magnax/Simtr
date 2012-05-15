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

//Spawn - utworzenie nowej postaci
$key = "global:event_tpl:Spawn";
$person = 1;
$this->redis->set("$key:$person", 'Znajdujesz się w miejscu X');$loaded++;
//$this->redis->del("$key:$person:params");
//$this->redis->lpush("$key:$person:params", 'name');$loaded++;
//$this->redis->lpush("$key:$person:params", 'res_id');$loaded++;
//$this->redis->lpush("$key:$person:params", 'amount');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz nową osobę, której jeszcze nie widziałeś, to %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'sndr');$loaded++;

//GiveRaw
$key = "global:event_tpl:GiveRaw";
$person = 1;
$this->redis->set("$key:$person", 'Podajesz %s gram %s do %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'rcpt');$loaded++;
$this->redis->lpush("$key:$person:params", 'res_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'amount');$loaded++;

$person = 2;
$this->redis->set("$key:$person", '%s podaje Tobie %s gram %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'amount');$loaded++;
$this->redis->rpush("$key:$person:params", 'res_id');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s podaje trochę %s do %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'res_id');$loaded++;
$this->redis->rpush("$key:$person:params", 'rcpt');$loaded++;

//TalkTo
$key = "global:event_tpl:TalkTo";
$person = 1;
$this->redis->set("$key:$person", 'Mówisz do %s: "%s"');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'rcpt');$loaded++;
$this->redis->rpush("$key:$person:params", 'text');$loaded++;

$person = 2;
$this->redis->set("$key:$person", '%s mówi do Ciebie: "%s"');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'text');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s mówi do %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'rcpt');$loaded++;

?>
