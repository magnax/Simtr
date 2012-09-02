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
$this->redis->set("$key:$person", 'Znajdujesz się w miejscu wyglądającym jak %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'loc_type');$loaded++;
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

//PutRaw
$key = "global:event_tpl:PutRaw";
$person = 1;
$this->redis->set("$key:$person", 'Odkładasz %sg %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'amount');$loaded++;
$this->redis->rpush("$key:$person:params", 'res_id');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s odkłada %s %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'amount');$loaded++;
$this->redis->rpush("$key:$person:params", 'res_id');$loaded++;

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

//TalkAll
$key = "global:event_tpl:TalkAll";
$person = 1;
$this->redis->set("$key:$person", 'Mówisz: "%s"');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'text');$loaded++;

$person = 3;
$this->redis->set("$key:$person", '%s mówi: "%s"');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'text');$loaded++;

//PointExit
$key = "global:event_tpl:PointExit";
$person = 1;
$this->redis->set("$key:$person", 'Wskazujesz drogę: %s do %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'exit_id');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s wskazuje drogę: %s do %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'exit_id');$loaded++;

//HitPerson
$key = "global:event_tpl:HitPerson";
$person = 1;
$this->redis->set("$key:$person", 'Atakujesz %s %s, używając %s, ofiara traci %d punktów życia');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'skill');$loaded++;
$this->redis->rpush("$key:$person:params", 'rcpt');$loaded++;
$this->redis->rpush("$key:$person:params", 'wpid');$loaded++;
$this->redis->rpush("$key:$person:params", 'dmg');$loaded++;

$person = 2;
$this->redis->set("$key:$person", '%s %s atakuje Cię, używając %s. Tracisz %d punktów życia, %s pochłania %d obrażeń');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'skill');$loaded++;
$this->redis->rpush("$key:$person:params", 'wpid');$loaded++;
$this->redis->rpush("$key:$person:params", 'dmg');$loaded++;
$this->redis->rpush("$key:$person:params", 'shid');$loaded++;
$this->redis->rpush("$key:$person:params", 'shd');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s %s atakuje %s, używając %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'skill');$loaded++;
$this->redis->rpush("$key:$person:params", 'rcpt');$loaded++;
$this->redis->rpush("$key:$person:params", 'wpid');$loaded++;

//PutItem
$key = "global:event_tpl:PutItem";
$person = 1;
$this->redis->set("$key:$person", 'Odkładasz %s %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'stt');$loaded++;
$this->redis->rpush("$key:$person:params", 'itemid');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s odkłada %s %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'stt');$loaded++;
$this->redis->rpush("$key:$person:params", 'itemid');$loaded++;

//GetItem
$key = "global:event_tpl:GetItem";
$person = 1;
$this->redis->set("$key:$person", 'Podnosisz %s %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'stt');$loaded++;
$this->redis->rpush("$key:$person:params", 'itemid');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s bierze %s %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'stt');$loaded++;
$this->redis->rpush("$key:$person:params", 'itemid');$loaded++;

?>
