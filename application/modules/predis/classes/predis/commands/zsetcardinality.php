<?php

class Predis_Commands_ZSetCardinality extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'ZCARD'; }
}

?>
