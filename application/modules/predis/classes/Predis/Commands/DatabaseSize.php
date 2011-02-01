<?php

class Predis_Commands_DatabaseSize extends Predis_MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'DBSIZE'; }
}

?>
