<?php

class Predis_Commands_Set extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SET'; }
}

?>
