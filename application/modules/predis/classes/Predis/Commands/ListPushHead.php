<?php

class Predis_Commands_ListPushHead extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'LPUSH'; }
}

?>
