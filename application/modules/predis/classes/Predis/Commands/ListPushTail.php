<?php

class Predis_Commands_ListPushTail extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'RPUSH'; }
}

?>
