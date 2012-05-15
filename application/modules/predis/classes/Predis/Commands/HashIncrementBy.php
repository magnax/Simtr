<?php

class Predis_Commands_HashIncrementBy extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'HINCRBY'; }
}

?>
