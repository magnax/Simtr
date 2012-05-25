<?php

class Predis_Commands_DecrementBy extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'DECRBY'; }
}

?>
