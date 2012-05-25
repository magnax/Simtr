<?php

class Predis_Commands_GetMultiple extends Predis_MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'MGET'; }
}

?>
