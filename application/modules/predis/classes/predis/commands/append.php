<?php

class Predis_Commands_Append extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'APPEND'; }
}

?>
