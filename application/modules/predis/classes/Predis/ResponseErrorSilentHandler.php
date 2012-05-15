<?php

class ResponseErrorSilentHandler implements Predis_IResponseHandler {
    public function handle(Predis_Connection $connection, $errorMessage) {
        return new Predis_ResponseError(substr($errorMessage, 4));
    }
}
?>
