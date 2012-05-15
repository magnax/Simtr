<?php

class Predis_Commands_ZSetRemoveRangeByRank extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'ZREMRANGEBYRANK'; }
}

?>
