<?php

class Predis_ResponseMultiBulkHandler implements Predis_IResponseHandler {
    public function handle(Predis_Connection $connection, $rawLength) {
        if (!is_numeric($rawLength)) {
            Predis_Shared_Utils::onCommunicationException(new Predis_MalformedServerResponse(
                $connection, "Cannot parse '$rawLength' as data length"
            ));
        }

        $listLength = (int) $rawLength;
        if ($listLength === -1) {
            return null;
        }

        $list = array();

        if ($listLength > 0) {
            $reader = $connection->getResponseReader();
            for ($i = 0; $i < $listLength; $i++) {
                $list[] = $reader->read($connection);
            }
        }

        return $list;
    }
}

?>
