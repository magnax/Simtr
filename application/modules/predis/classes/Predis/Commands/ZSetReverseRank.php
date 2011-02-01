<?php

class Predis_Commands_ZSetReverseRank extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'ZREVRANK'; }
}

?>
