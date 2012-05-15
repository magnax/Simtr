<?php

abstract class Predis_BulkCommand extends Predis_Command {
    public function serializeRequest($command, $arguments) {
        $data = array_pop($arguments);
        if (is_array($data)) {
            $data = implode($data, ' ');
        }
        return $command . ' ' . implode($arguments, ' ') . ' ' . strlen($data) .
            Predis_Protocol::NEWLINE . $data . Predis_Protocol::NEWLINE;
    }
}

?>
