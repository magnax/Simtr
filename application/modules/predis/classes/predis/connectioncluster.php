<?php

class Predis_ConnectionCluster implements Predis_IConnection, IteratorAggregate {
    private $_pool, $_distributor;

    public function __construct(Predis_Distribution_IDistributionStrategy $distributor = null) {
        $this->_pool = array();
        $this->_distributor = $distributor !== null ? $distributor : new Predis_Distribution_HashRing();
    }

    public function isConnected() {
        foreach ($this->_pool as $connection) {
            if ($connection->isConnected()) {
                return true;
            }
        }
        return false;
    }

    public function connect() {
        foreach ($this->_pool as $connection) {
            $connection->connect();
        }
    }

    public function disconnect() {
        foreach ($this->_pool as $connection) {
            $connection->disconnect();
        }
    }

    public function add(Predis_Connection $connection) {
        $parameters = $connection->getParameters();
        if (isset($parameters->alias)) {
            $this->_pool[$parameters->alias] = $connection;
        }
        else {
            $this->_pool[] = $connection;
        }
        $this->_distributor->add($connection, $parameters->weight);
    }

    public function getConnection(Predis_Command $command) {
        if ($command->canBeHashed() === false) {
            throw new Predis_ClientException(
                sprintf("Cannot send '%s' commands to a cluster of connections", $command->getCommandId())
            );
        }
        return $this->_distributor->get($command->getHash($this->_distributor));
    }

    public function getConnectionById($id = null) {
        $alias = $id !== null ? $id : 0;
        return isset($this->_pool[$alias]) ? $this->_pool[$alias] : null;
    }

    public function getIterator() {
        return new ArrayIterator($this->_pool);
    }

    public function writeCommand(Predis_Command $command) {
        $this->getConnection($command)->writeCommand($command);
    }

    public function readResponse(Predis_Command $command) {
        return $this->getConnection($command)->readResponse($command);
    }

    public function executeCommand(Predis_Command $command) {
        $connection = $this->getConnection($command);
        $connection->writeCommand($command);
        return $connection->readResponse($command);
    }
}

?>
