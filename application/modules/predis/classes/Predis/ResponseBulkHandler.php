<?php

class Predis_ResponseBulkHandler implements Predis_IResponseHandler {
    public function handle(Predis_Connection $connection, $dataLength) {
        if (!is_numeric($dataLength)) {
            Predis_Shared_Utils::onCommunicationException(new Predis_MalformedServerResponse(
                $connection, "Cannot parse '$dataLength' as data length"
            ));
        }

        if ($dataLength > 0) {
            $value = $connection->readBytes($dataLength);
            self::discardNewLine($connection);
            return $value;
        }
        else if ($dataLength == 0) {
            self::discardNewLine($connection);
            return '';
        }

        return null;
    }

    private static function discardNewLine(Predis_Connection $connection) {
        if ($connection->readBytes(2) !== Predis_Protocol::NEWLINE) {
            Predis_Shared_Utils::onCommunicationException(new Predis_MalformedServerResponse(
                $connection, 'Did not receive a new-line at the end of a bulk response'
            ));
        }
    }
}
?>
