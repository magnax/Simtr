<?php

class Predis_Commands_SetUnion extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SUNION'; }
}

?>
