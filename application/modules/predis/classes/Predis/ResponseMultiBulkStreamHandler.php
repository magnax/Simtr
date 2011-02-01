<?php

class Predis_ResponseMultiBulkStreamHandler implements Predis_IResponseHandler {
    public function handle(Predis_Connection $connection, $rawLength) {
        if (!is_numeric($rawLength)) {
            Predis_Shared_Utils::onCommunicationException(new Predis_MalformedServerResponse(
                $connection, "Cannot parse '$rawLength' as data length"
            ));
        }
        return new Predis_Shared_MultiBulkResponseIterator($connection, (int)$rawLength);
    }
}

?>
