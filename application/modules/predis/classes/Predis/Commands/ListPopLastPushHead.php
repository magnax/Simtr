<?php

class Predis_Commands_ListPopLastPushHead extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'RPOPLPUSH'; }
}

?>
