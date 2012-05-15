<?php

class Predis_Commands_HashValues extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'HVALS'; }
}

?>
