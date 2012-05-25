<?php

class Predis_Commands_ZSetReverseRange extends Predis_Commands_ZSetRange {
    public function getCommandId() { return 'ZREVRANGE'; }
}

?>
