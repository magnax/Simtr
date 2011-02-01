<?php

class Predis_Commands_HashKeys extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'HKEYS'; }
}

?>
