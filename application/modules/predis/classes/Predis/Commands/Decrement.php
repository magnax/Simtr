<?php

class Predis_Commands_Decrement extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'DECR'; }
}

?>
