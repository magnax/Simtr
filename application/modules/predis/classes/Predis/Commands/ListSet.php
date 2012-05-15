<?php

class Predis_Commands_ListSet extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'LSET'; }
}

?>
