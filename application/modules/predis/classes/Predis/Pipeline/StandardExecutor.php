<?php

class Predis_Pipeline_StandardExecutor implements Predis_Pipeline_IPipelineExecutor {
    public function execute(Predis_IConnection $connection, &$commands) {
        $sizeofPipe = count($commands);
        $values = array();

        foreach ($commands as $command) {
            $connection->writeCommand($command);
        }
        try {
            for ($i = 0; $i < $sizeofPipe; $i++) {
                $response = $connection->readResponse($commands[$i]);
                $values[] = $response instanceof Iterator
                    ? iterator_to_array($response)
                    : $response;
                unset($commands[$i]);
            }
        }
        catch (Predis_ServerException $exception) {
            // force disconnection to prevent protocol desynchronization
            $connection->disconnect();
            throw $exception;
        }

        return $values;
    }
}

?>
