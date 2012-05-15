<?php

class Predis_Commands_Increment extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'INCR'; }
}

?>
