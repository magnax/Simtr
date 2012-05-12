<?php

class Predis_Commands_ListPushHeadX extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'LPUSHX'; }
}

?>
