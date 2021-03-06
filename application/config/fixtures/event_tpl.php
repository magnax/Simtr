<?php

//ArriveInfo
$key = "global:event_tpl:ArriveInfo";
$person = 1;
$this->redis->set("$key:$person", 'Przybywasz do %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'location_id');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s przybywa do %s drogą z %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'from_location_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'location_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'sndr');$loaded++;

//BuildEnd
$key = "global:event_tpl:BuildEnd";

$person = 1;
$this->redis->set("$key:$person", 'Projekt budowy: %s o nazwie: "%s" zakończony');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'itemtype_id');$loaded++;
$this->redis->rpush("$key:$person:params", 'building_name');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Projekt budowy: %s o nazwie: "%s" zakończony');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'itemtype_id');$loaded++;
$this->redis->rpush("$key:$person:params", 'building_name');$loaded++;

//BuryEnd
$key = "global:event_tpl:BuryEnd";
$person = 1;
$this->redis->set("$key:$person", 'Projekt: "zakopywanie ciała" został zakończony');$loaded++;
$this->redis->del("$key:$person:params");

$person = 3;
$this->redis->set("$key:$person", 'Projekt: "zakopywanie ciała" został zakończony');$loaded++;
$this->redis->del("$key:$person:params");

//DepartureInfo
$key = "global:event_tpl:DepartureInfo";
$person = 1;
$this->redis->set("$key:$person", 'Opuszczasz %s drogą do %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'location_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'from_location_id');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s opuszcza %s drogą do %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'location_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'from_location_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'sndr');$loaded++;

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
$this->redis->set("$key:$person", 'Projekt %s zakończony, %s gram %s trafia do Twojego ekwipunku');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'res_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'amount');$loaded++;
$this->redis->lpush("$key:$person:params", 'name');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Projekt %s zakończony, %s gram %s trafia do ekwipunku %s');$loaded++;
$this->redis->del("$key:$person:params");

$this->redis->lpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->lpush("$key:$person:params", 'res_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'amount');$loaded++;
$this->redis->lpush("$key:$person:params", 'name');$loaded++;

//GetRawEndGround - surowiec na ziemię
$key = "global:event_tpl:GetRawEndGround";
$person = 1;
$this->redis->set("$key:$person", 'Projekt %s zakończony, %s gram %s trafia na ziemię');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'res_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'amount');$loaded++;
$this->redis->lpush("$key:$person:params", 'name');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Projekt %s zakończony, %s gram %s trafia na ziemię');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'res_id');$loaded++;
$this->redis->lpush("$key:$person:params", 'amount');$loaded++;
$this->redis->lpush("$key:$person:params", 'name');$loaded++;

//LocationInfo
$key = "global:event_tpl:LocationInfo";
$person = 1;
$this->redis->set("$key:$person", 'Znajdujesz się w miejscu, które wygląda jak %s, widzisz tu %s innych osób');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->lpush("$key:$person:params", 'characters_count');$loaded++;
$this->redis->lpush("$key:$person:params", 'location_type');$loaded++;

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
$this->redis->set("$key:$person", 'Odkładasz %s gram %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'amount');$loaded++;
$this->redis->rpush("$key:$person:params", 'res_id');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s odkłada %s %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'amount');$loaded++;
$this->redis->rpush("$key:$person:params", 'res_id');$loaded++;

//UseRaw
$key = "global:event_tpl:UseRaw";
$person = 1;
$this->redis->set("$key:$person", 'Używasz %s gram %s do projektu %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'amount');$loaded++;
$this->redis->rpush("$key:$person:params", 'res_id');$loaded++;
$this->redis->rpush("$key:$person:params", 'project_name');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s używa trochę %s do projektu %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'res_id');$loaded++;
$this->redis->rpush("$key:$person:params", 'project_name');$loaded++;

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

//GodTalk
$key = "global:event_tpl:GodTalk";
$person = 1;
$this->redis->set("$key:$person", 'Mówisz: "%s"');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'text');$loaded++;

$person = 3;
$this->redis->set("$key:$person", '<span style="font-weight:bold;color:#36D986;background-color:#fff;padding:3px;">Słyszysz głos potężny, z nieba się rozlegający, który oznajmia: </span><span style="font-weight:bold;color:#ff0000;background-color:#fff;padding:3px;">%s</span>');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'text');$loaded++;

//PointExit
$key = "global:event_tpl:PointExit";
$person = 1;
$this->redis->set("$key:$person", 'Wskazujesz drogę: %s do %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'road_id');$loaded++;
$this->redis->rpush("$key:$person:params", 'exit_id');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s wskazuje drogę: %s do %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'road_id');$loaded++;
$this->redis->rpush("$key:$person:params", 'exit_id');$loaded++;

//PointPerson
$key = "global:event_tpl:PointPerson";
$person = 1;
$this->redis->set("$key:$person", 'Wskazujesz %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'rcpt');$loaded++;

$person = 2;
$this->redis->set("$key:$person", '%s wskazuje Ciebie');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s wskazuje %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'rcpt');$loaded++;

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

//KillPerson
$key = "global:event_tpl:KillPerson";
$person = 1;
$this->redis->set("$key:$person", '%s zabijasz %s, używając %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'skill');$loaded++;
$this->redis->rpush("$key:$person:params", 'rcpt');$loaded++;
$this->redis->rpush("$key:$person:params", 'wpid');$loaded++;

$person = 2;
$this->redis->set("$key:$person", '%s %s zabija Cię, używając %s. To koniec.');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'skill');$loaded++;
$this->redis->rpush("$key:$person:params", 'wpid');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s %s zabija %s, używając %s');$loaded++;
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
$this->redis->rpush("$key:$person:params", 'item_points');$loaded++;
$this->redis->rpush("$key:$person:params", 'item_id');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s odkłada %s %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'item_points');$loaded++;
$this->redis->rpush("$key:$person:params", 'item_id');$loaded++;

//GetItem
$key = "global:event_tpl:GetItem";
$person = 1;
$this->redis->set("$key:$person", 'Podnosisz %s %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'item_points');$loaded++;
$this->redis->rpush("$key:$person:params", 'item_id');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s bierze %s %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'item_points');$loaded++;
$this->redis->rpush("$key:$person:params", 'item_id');$loaded++;

//GiveItem
$key = "global:event_tpl:GiveItem";
$person = 1;
$this->redis->set("$key:$person", 'Podajesz %s %s do %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'item_points');$loaded++;
$this->redis->rpush("$key:$person:params", 'item_id');$loaded++;
$this->redis->rpush("$key:$person:params", 'rcpt');$loaded++;

$person = 2;
$this->redis->set("$key:$person", '%s podaje Ci %s %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'item_points');$loaded++;
$this->redis->rpush("$key:$person:params", 'item_id');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s podaje %s %s do %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'item_points');$loaded++;
$this->redis->rpush("$key:$person:params", 'item_id');$loaded++;
$this->redis->rpush("$key:$person:params", 'rcpt');$loaded++;

//EnterLocation
$key = "global:event_tpl:EnterLocation";
$person = 1;
$this->redis->set("$key:$person", 'Wchodzisz do %s, opuszczając %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'locid');$loaded++;
$this->redis->rpush("$key:$person:params", 'exit_id');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s wchodzi do %s, opuszczając %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'locid');$loaded++;
$this->redis->rpush("$key:$person:params", 'exit_id');$loaded++;

//MakeEnd
$key = "global:event_tpl:MakeEnd";

$person = 1;
$this->redis->set("$key:$person", 'Projekt %s zakończony');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'name');$loaded++;
$this->redis->rpush("$key:$person:params", 'itemtypeid');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Projekt %s zakończony');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'name');$loaded++;
$this->redis->rpush("$key:$person:params", 'itemtypeid');$loaded++;

//MakeEndGround
$key = "global:event_tpl:MakeEndGround";

$person = 1;
$this->redis->set("$key:$person", 'Projekt %s zakończony');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'name');$loaded++;
$this->redis->rpush("$key:$person:params", 'itemtypeid');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Projekt %s zakończony');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'name');$loaded++;
$this->redis->rpush("$key:$person:params", 'itemtypeid');$loaded++;

//Hungry
$key = "global:event_tpl:Hungry";

$person = 1;
$this->redis->set("$key:$person", 'Głodujesz');$loaded++;
$this->redis->del("$key:$person:params");

//Eat
$key = "global:event_tpl:Eat";

$person = 1;
$this->redis->set("$key:$person", 'Zjadasz %s');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'desc');$loaded++;

//PutNote
$key = "global:event_tpl:PutNote";

$person = 1;
$this->redis->set("$key:$person", 'Odkładasz notatkę "%s"');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'note_title');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s odkłada notatkę "%s"');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'note_title');$loaded++;

//GetNote
$key = "global:event_tpl:GetNote";

$person = 1;
$this->redis->set("$key:$person", 'Podnosisz notatkę "%s"');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'note_title');$loaded++;

$person = 3;
$this->redis->set("$key:$person", 'Widzisz jak %s podnosi notatkę "%s"');$loaded++;
$this->redis->del("$key:$person:params");
$this->redis->rpush("$key:$person:params", 'sndr');$loaded++;
$this->redis->rpush("$key:$person:params", 'note_title');$loaded++;

?>
