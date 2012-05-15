<?php

class Predis_Commands_IncrementBy extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'INCRBY'; }
}

?>
