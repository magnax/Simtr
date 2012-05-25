<?php

class Predis_Commands_SetCardinality extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SCARD'; }
}

?>
