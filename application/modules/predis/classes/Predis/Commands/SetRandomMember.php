<?php

class Predis_Commands_SetRandomMember extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SRANDMEMBER'; }
}

?>
