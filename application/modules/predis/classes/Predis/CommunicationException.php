<?php

// Communication errors
class Predis_CommunicationException extends PredisException {
    private $_connection;

    public function __construct(Predis_Connection $connection, $message = null, $code = null) {
        $this->_connection = $connection;
        parent::__construct($message, $code);
    }

    public function getConnection() { return $this->_connection; }
    public function shouldResetConnection() {  return true; }
}

?>
