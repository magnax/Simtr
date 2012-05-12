<?php

class Predis_Commands_SetIntersection extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SINTER'; }
}

?>
