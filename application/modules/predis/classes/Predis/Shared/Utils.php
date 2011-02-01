<?php

class Predis_Shared_Utils {
    public static function isCluster(Predis_IConnection $connection) {
        return $connection instanceof Predis_ConnectionCluster;
    }

    public static function onCommunicationException(Predis_CommunicationException $exception) {
        if ($exception->shouldResetConnection()) {
            $connection = $exception->getConnection();
            if ($connection->isConnected()) {
                $connection->disconnect();
            }
        }
        throw $exception;
    }
}

?>
