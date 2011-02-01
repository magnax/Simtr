<?php

class Predis_Commands_Shutdown extends Predis_MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'SHUTDOWN'; }
    public function closesConnection() { return true; }
}

?>
