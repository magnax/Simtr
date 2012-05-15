<?php

class Predis_Commands_ListRange extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'LRANGE'; }
}

?>
