<?php

class Predis_ResponseErrorHandler implements Predis_IResponseHandler {
    public function handle(Predis_Connection $connection, $errorMessage) {
        throw new Predis_ServerException(substr($errorMessage, 4));
    }
}
?>
