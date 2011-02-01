<?php

class Predis_Commands_BackgroundSave extends Predis_MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'BGSAVE'; }
    public function parseResponse($data) {
        if ($data == 'Background saving started') {
            return true;
        }
        return $data;
    }
}

?>
