<?php

class Predis_Commands_SetDifferenceStore extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SDIFFSTORE'; }
}

?>
