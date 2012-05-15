<?php

class Predis_Commands_SetPop  extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SPOP'; }
}

?>
