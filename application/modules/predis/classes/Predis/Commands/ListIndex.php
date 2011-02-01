<?php

class Predis_Commands_ListIndex extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'LINDEX'; }
}

?>
