<?php

abstract class Predis_InlineCommand extends Predis_Command {
    public function serializeRequest($command, $arguments) {
        if (isset($arguments[0]) && is_array($arguments[0])) {
            $arguments[0] = implode($arguments[0], ' ');
        }
        return $command . (count($arguments) > 0
            ? ' ' . implode($arguments, ' ') . Predis_Protocol::NEWLINE
            : Predis_Protocol::NEWLINE
        );
    }
}

?>
