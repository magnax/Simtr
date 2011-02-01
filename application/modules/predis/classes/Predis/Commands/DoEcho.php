<?php

class Predis_Commands_DoEcho extends Predis_MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'ECHO'; }
}

?>
