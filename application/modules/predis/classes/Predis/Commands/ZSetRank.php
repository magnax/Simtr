<?php

class Predis_Commands_ZSetRank extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'ZRANK'; }
}

?>
