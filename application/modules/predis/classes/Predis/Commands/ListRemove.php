<?php

class Predis_Commands_ListRemove extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'LREM'; }
}

?>
