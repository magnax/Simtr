<?php

class Predis_Commands_SubscribeByPattern extends Predis_MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'PSUBSCRIBE'; }
}

?>
