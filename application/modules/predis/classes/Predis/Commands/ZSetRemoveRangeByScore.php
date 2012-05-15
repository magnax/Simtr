<?php

class Predis_Commands_ZSetRemoveRangeByScore extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'ZREMRANGEBYSCORE'; }
}

?>
