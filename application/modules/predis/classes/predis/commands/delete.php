<?php

class Predis_Commands_Delete extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'DEL'; }
}

?>
