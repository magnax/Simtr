<?php

class Predis_Commands_ZSetIncrementBy extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'ZINCRBY'; }
}

?>
