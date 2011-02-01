<?php

class Predis_Commands_ListInsert extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'LINSERT'; }
}

?>
