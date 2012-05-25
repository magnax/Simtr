<?php

abstract class Predis_MultiBulkCommand extends Predis_Command {
    public function serializeRequest($command, $arguments) {
        $cmd_args = null;
        $argsc    = count($arguments);

        if ($argsc === 1 && is_array($arguments[0])) {
            $cmd_args = $arguments[0];
            $argsc = count($cmd_args);
        }
        else {
            $cmd_args = $arguments;
        }

        $newline = Predis_Protocol::NEWLINE;
        $cmdlen  = strlen($command);
        $reqlen  = $argsc + 1;

        $buffer = "*{$reqlen}{$newline}\${$cmdlen}{$newline}{$command}{$newline}";
        foreach ($cmd_args as $argument) {
            $arglen  = strlen($argument);
            $buffer .= "\${$arglen}{$newline}{$argument}{$newline}";
        }

        return $buffer;
    }
}

?>
