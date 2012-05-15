<?php

class Predis_Commands_ZSetReverseRangeByScore extends Predis_Commands_ZSetRangeByScore {
    public function getCommandId() { return 'ZREVRANGEBYSCORE'; }
}

?>
