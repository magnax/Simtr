<?php

class Predis_Commands_SetMembers extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SMEMBERS'; }
}

?>
