<?php

class Predis_Commands_UnsubscribeByPattern extends Predis_MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'PUNSUBSCRIBE'; }
}

?>
