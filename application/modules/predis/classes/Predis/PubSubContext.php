<?php

class Predis_PubSubContext implements Iterator {
    const SUBSCRIBE    = 'subscribe';
    const UNSUBSCRIBE  = 'unsubscribe';
    const PSUBSCRIBE   = 'psubscribe';
    const PUNSUBSCRIBE = 'punsubscribe';
    const MESSAGE      = 'message';
    const PMESSAGE     = 'pmessage';

    const STATUS_VALID       = 0x0001;
    const STATUS_SUBSCRIBED  = 0x0010;
    const STATUS_PSUBSCRIBED = 0x0100;

    private $_redisClient, $_position;

    public function __construct(Predis_Client $redisClient) {
        $this->checkCapabilities($redisClient);
        $this->_redisClient = $redisClient;
        $this->_statusFlags = self::STATUS_VALID;
    }

    public function __destruct() {
        if ($this->valid()) {
            $this->closeContext();
        }
    }

    private function checkCapabilities(Predis_Client $redisClient) {
        if (Predis_Shared_Utils::isCluster($redisClient->getConnection())) {
            throw new Predis_ClientException(
                'Cannot initialize a PUB/SUB context over a cluster of connections'
            );
        }
        $profile = $redisClient->getProfile();
        $commands = array('publish', 'subscribe', 'unsubscribe', 'psubscribe', 'punsubscribe');
        if ($profile->supportsCommands($commands) === false) {
            throw new Predis_ClientException(
                'The current profile does not support PUB/SUB related commands'
            );
        }
    }

    private function isFlagSet($value) {
        return ($this->_statusFlags & $value) === $value;
    }

    public function subscribe(/* arguments */) {
        $args = func_get_args();
        $this->writeCommand(self::SUBSCRIBE, $args);
        $this->_statusFlags |= self::STATUS_SUBSCRIBED;
    }

    public function unsubscribe(/* arguments */) {
        $args = func_get_args();
        $this->writeCommand(self::UNSUBSCRIBE, $args);
    }

    public function psubscribe(/* arguments */) {
        $args = func_get_args();
        $this->writeCommand(self::PSUBSCRIBE, $args);
        $this->_statusFlags |= self::STATUS_PSUBSCRIBED;
    }

    public function punsubscribe(/* arguments */) {
        $args = func_get_args();
        $this->writeCommand(self::PUNSUBSCRIBE, $args);
    }

    public function closeContext() {
        if ($this->valid()) {
            if ($this->isFlagSet(self::STATUS_SUBSCRIBED)) {
                $this->unsubscribe();
            }
            if ($this->isFlagSet(self::STATUS_PSUBSCRIBED)) {
                $this->punsubscribe();
            }
        }
    }

    private function writeCommand($method, $arguments) {
        if (count($arguments) === 1 && is_array($arguments[0])) {
            $arguments = $arguments[0];
        }
        $command = $this->_redisClient->createCommand($method, $arguments);
        $this->_redisClient->getConnection()->writeCommand($command);
    }

    public function rewind() {
        // NOOP
    }

    public function current() {
        return $this->getValue();
    }

    public function key() {
        return $this->_position;
    }

    public function next() {
        if ($this->isFlagSet(self::STATUS_VALID)) {
            $this->_position++;
        }
        return $this->_position;
    }

    public function valid() {
        $subscriptions = self::STATUS_SUBSCRIBED + self::STATUS_PSUBSCRIBED;
        return $this->isFlagSet(self::STATUS_VALID)
            && ($this->_statusFlags & $subscriptions) > 0;
    }

    private function invalidate() {
        $this->_statusFlags = 0x0000;
    }

    private function getValue() {
        $reader     = $this->_redisClient->getResponseReader();
        $connection = $this->_redisClient->getConnection();
        $response   = $reader->read($connection);

        switch ($response[0]) {
            case self::SUBSCRIBE:
            case self::UNSUBSCRIBE:
            case self::PSUBSCRIBE:
            case self::PUNSUBSCRIBE:
                if ($response[2] === 0) {
                    $this->invalidate();
                }
            case self::MESSAGE:
                return (object) array(
                    'kind'    => $response[0],
                    'channel' => $response[1],
                    'payload' => $response[2],
                );
            case self::PMESSAGE:
                return (object) array(
                    'kind'    => $response[0],
                    'pattern' => $response[1],
                    'channel' => $response[2],
                    'payload' => $response[3],
                );
            default:
                throw new Predis_ClientException(
                    "Received an unknown message type {$response[0]} inside of a pubsub context"
                );
        }
    }
}

?>
