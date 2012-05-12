<?php
class Predis_ResponseReader {
    private $_prefixHandlers;

    public function __construct() {
        $this->initializePrefixHandlers();
    }

    private function initializePrefixHandlers() {
        $this->_prefixHandlers = array(
            Predis_Protocol::PREFIX_STATUS     => new Predis_ResponseStatusHandler(),
            Predis_Protocol::PREFIX_ERROR      => new Predis_ResponseErrorHandler(),
            Predis_Protocol::PREFIX_INTEGER    => new Predis_ResponseIntegerHandler(),
            Predis_Protocol::PREFIX_BULK       => new Predis_ResponseBulkHandler(),
            Predis_Protocol::PREFIX_MULTI_BULK => new Predis_ResponseMultiBulkHandler(),
        );
    }

    public function setHandler($prefix, Predis_IResponseHandler $handler) {
        $this->_prefixHandlers[$prefix] = $handler;
    }

    public function getHandler($prefix) {
        if (isset($this->_prefixHandlers[$prefix])) {
            return $this->_prefixHandlers[$prefix];
        }
    }

    public function read(Predis_Connection $connection) {
        $header = $connection->readLine();
        if ($header === '') {
            Predis_Shared_Utils::onCommunicationException(new Predis_MalformedServerResponse(
                $connection, 'Unexpected empty header'
            ));
        }

        $prefix  = $header[0];
        $payload = strlen($header) > 1 ? substr($header, 1) : '';

        if (!isset($this->_prefixHandlers[$prefix])) {
            Predis_Shared_Utils::onCommunicationException(new Predis_MalformedServerResponse(
                $connection, "Unknown prefix '$prefix'"
            ));
        }

        $handler = $this->_prefixHandlers[$prefix];
        return $handler->handle($connection, $payload);
    }
}

?>
