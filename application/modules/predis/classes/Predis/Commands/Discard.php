<?php

class Predis_Commands_Discard extends Predis_MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'DISCARD'; }
}

?>
