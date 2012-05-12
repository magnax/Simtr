<?php

class Predis_Commands_SetIntersectionStore extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SINTERSTORE'; }
}

?>
