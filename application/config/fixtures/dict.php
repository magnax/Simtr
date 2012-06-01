<?php

$key = "dict:pl:K";
$strings = array(
    20=>'dwudziestoletnia',
    30=>'trzydziestoletnia',
    40=>'czterdziestoletnia',
    50=>'pięćdziesięcioletnia',
    60=>'sześćdziesięcioletnia',
    70=>'siedemdziesięcioletnia',
    80=>'osiemdziesięcioletnia',
    'old'=>'bardzo stara',
);
foreach ($strings as $k=>$v) {
    $this->redis->set("$key:$k", $v);
    $loaded++;
}

$key = "dict:pl:M";
$strings = array(
    20=>'dwudziestoletni',
    30=>'trzydziestoletni',
    40=>'czterdziestoletni',
    50=>'pięćdziesięcioletni',
    60=>'sześćdziesięcioletni',
    70=>'siedemdziesięcioletni',
    80=>'osiemdziesięcioletni',
    'old'=>'bardzo stary',
);
foreach ($strings as $k=>$v) {
    $this->redis->set("$key:$k", $v);
    $loaded++;
}

unset($key);
unset($strings);

$this->redis->set("dict:pl:weapons", 'Broń');$loaded++;
$this->redis->set("dict:pl:grab_wood", 'Zbieranie drewna');$loaded++;
$this->redis->set("dict:pl:stick", 'Kij');$loaded++;
$this->redis->set("dict:pl:desert", "pustynia");$loaded++;

?>
