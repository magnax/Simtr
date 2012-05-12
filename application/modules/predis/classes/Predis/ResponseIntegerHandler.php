<?php

class Predis_ResponseIntegerHandler implements Predis_IResponseHandler {
    public function handle(Predis_Connection $connection, $number) {
        if (is_numeric($number)) {
            return (int) $number;
        }
        else {
            if ($number !== Predis_Protocol::NULL) {
                Predis_Shared_Utils::onCommunicationException(new Predis_MalformedServerResponse(
                    $connection, "Cannot parse '$number' as numeric response"
                ));
            }
            return null;
        }
    }
}

?>
