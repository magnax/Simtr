<?php

class Predis_Commands_HashGet extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'HGET'; }
}

?>
