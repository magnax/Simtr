<?php

class Predis_Commands_ListPopLast extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'RPOP'; }
}

?>
