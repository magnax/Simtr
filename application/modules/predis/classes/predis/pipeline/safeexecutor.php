<?php

class Predis_Pipeline_SafeExecutor implements Predis_Pipeline_IPipelineExecutor {
    public function execute(Predis_IConnection $connection, &$commands) {
        $sizeofPipe = count($commands);
        $values = array();

        foreach ($commands as $command) {
            try {
                $connection->writeCommand($command);
            }
            catch (Predis_CommunicationException $exception) {
                return array_fill(0, $sizeofPipe, $exception);
            }
        }

        for ($i = 0; $i < $sizeofPipe; $i++) {
            $command = $commands[$i];
            unset($commands[$i]);
            try {
                $response = $connection->readResponse($command);
                $values[] = ($response instanceof Iterator
                    ? iterator_to_array($response)
                    : $response
                );
            }
            catch (Predis_ServerException $exception) {
                $values[] = $exception->toResponseError();
            }
            catch (Predis_CommunicationException $exception) {
                $toAdd  = count($commands) - count($values);
                $values = array_merge($values, array_fill(0, $toAdd, $exception));
                break;
            }
        }

        return $values;
    }
}

?>
