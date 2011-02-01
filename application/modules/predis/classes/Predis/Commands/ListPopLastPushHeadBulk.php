<?php

class Predis_Commands_ListPopLastPushHeadBulk extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'RPOPLPUSH'; }
}

?>
