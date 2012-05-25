<?php

class Predis_Commands_GetSet extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'GETSET'; }
}

?>
