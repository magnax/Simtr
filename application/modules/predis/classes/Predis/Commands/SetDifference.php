<?php

class Predis_Commands_SetDifference extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SDIFF'; }
}

?>
