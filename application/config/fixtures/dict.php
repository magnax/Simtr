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

/**
 * Translations for location type 
 */
$this->redis->set("dict:pl:desert", "pustynia");$loaded++;
$this->redis->set("dict:pl:grass", "łąka");$loaded++;
$this->redis->set("dict:pl:mountains", "góry");$loaded++;
$this->redis->set("dict:pl:forest", "las");$loaded++;
$this->redis->set("dict:pl:hills", "wzgórza");$loaded++;

//skills
$this->redis->set("dict:pl:fight0.8", "niezręcznie");$loaded++;
$this->redis->set("dict:pl:fight0.9", "po amatorsku");$loaded++;
$this->redis->set("dict:pl:fight1", "przeciętnie");$loaded++;
$this->redis->set("dict:pl:fight1.1", "umiejętnie");$loaded++;
$this->redis->set("dict:pl:fight1.2", "po mistrzowsku");$loaded++;

//items
$this->redis->set("dict:pl:(none)", "(brak)");$loaded++;
$this->redis->set("dict:pl:bare_fist", "gołe pięści");$loaded++;
$this->redis->set("dict:pl:bone_knife", "kościany nóż");$loaded++;
$this->redis->set("dict:pl:small_bone_shield", "mała kościana tarcza");$loaded++;

//no cóż... w języku polskim rodzaj jest ważny ;)
$this->redis->set("kind:pl:bone_knife", "m");$loaded++;
$this->redis->set("kind:pl:small_bone_shield", "f");$loaded++;

//item state
$this->redis->set("dict:pl:brand_new:m", "całkiem nowy");$loaded++;
$this->redis->set("dict:pl:brand_new:f", "całkiem nowa");$loaded++;
$this->redis->set("dict:pl:brand_new:n", "całkiem nowe");$loaded++;
$this->redis->set("dict:pl:new:m", "nowy");$loaded++;
$this->redis->set("dict:pl:new:f", "nowa");$loaded++;
$this->redis->set("dict:pl:new:n", "nowe");$loaded++;
$this->redis->set("dict:pl:used:m", "używany");$loaded++;
$this->redis->set("dict:pl:used:f", "używana");$loaded++;
$this->redis->set("dict:pl:used:n", "używane");$loaded++;
$this->redis->set("dict:pl:often_used:m", "często używany");$loaded++;
$this->redis->set("dict:pl:often_used:f", "często używana");$loaded++;
$this->redis->set("dict:pl:often_used:n", "często używane");$loaded++;
$this->redis->set("dict:pl:crumbling:m", "zużyty");$loaded++;
$this->redis->set("dict:pl:crumbling:f", "zużyta");$loaded++;
$this->redis->set("dict:pl:crumbling:n", "zużyte");$loaded++;

?>
