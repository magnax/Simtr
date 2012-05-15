<?php

class Predis_Commands_ListPushTailX extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'RPUSHX'; }
}

?>
