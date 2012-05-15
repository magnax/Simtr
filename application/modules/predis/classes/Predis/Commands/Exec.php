<?php

class Predis_Commands_Exec extends Predis_MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'EXEC'; }
}

?>
