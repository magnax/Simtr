<?php

class Predis_Commands_ZSetCount extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'ZCOUNT'; }
}

?>
