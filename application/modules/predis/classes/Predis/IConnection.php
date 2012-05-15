<?php

interface Predis_IConnection {
    public function connect();
    public function disconnect();
    public function isConnected();
    public function writeCommand(Predis_Command $command);
    public function readResponse(Predis_Command $command);
    public function executeCommand(Predis_Command $command);
}

?>
