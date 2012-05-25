<?php

class Predis_Commands_Keys extends Predis_MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'KEYS'; }
    public function parseResponse($data) {
        // TODO: is this behaviour correct?
        if (is_array($data) || $data instanceof Iterator) {
            return $data;
        }
        return strlen($data) > 0 ? explode(' ', $data) : array();
    }
}

?>
