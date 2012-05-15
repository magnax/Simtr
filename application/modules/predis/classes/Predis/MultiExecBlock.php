<?php

class Predis_MultiExecBlock {
    private $_initialized, $_discarded, $_insideBlock;
    private $_redisClient, $_options, $_commands;
    private $_supportsWatch;

    public function __construct(Predis_Client $redisClient, Array $options = null) {
        $this->checkCapabilities($redisClient);
        $this->_initialized = false;
        $this->_discarded   = false;
        $this->_insideBlock = false;
        $this->_redisClient = $redisClient;
        $this->_options     = isset($options) ? $options : array();
        $this->_commands    = array();
    }

    private function checkCapabilities(Predis_Client $redisClient) {
        if (Predis_Shared_Utils::isCluster($redisClient->getConnection())) {
            throw new Predis_ClientException(
                'Cannot initialize a MULTI/EXEC context over a cluster of connections'
            );
        }
        $profile = $redisClient->getProfile();
        if ($profile->supportsCommands(array('multi', 'exec', 'discard')) === false) {
            throw new Predis_ClientException(
                'The current profile does not support MULTI, EXEC and DISCARD commands'
            );
        }
        $this->_supportsWatch = $profile->supportsCommands(array('watch', 'unwatch'));
    }

    private function isWatchSupported() {
        if ($this->_supportsWatch === false) {
            throw new Predis_ClientException(
                'The current profile does not support WATCH and UNWATCH commands'
            );
        }
    }

    private function initialize() {
        if ($this->_initialized === false) {
            if (isset($this->_options['watch'])) {
                $this->watch($this->_options['watch']);
            }
            $this->_redisClient->multi();
            $this->_initialized = true;
            $this->_discarded   = false;
        }
    }

    private function setInsideBlock($value) {
        $this->_insideBlock = $value;
    }

    public function __call($method, $arguments) {
        $this->initialize();
        $command  = $this->_redisClient->createCommand($method, $arguments);
        $response = $this->_redisClient->executeCommand($command);
        if (isset($response->queued)) {
            $this->_commands[] = $command;
            return $this;
        }
        else {
            $this->malformedServerResponse('The server did not respond with a QUEUED status reply');
        }
    }

    public function watch($keys) {
        $this->isWatchSupported();
        if ($this->_initialized === true) {
            throw new Predis_ClientException('WATCH inside MULTI is not allowed');
        }

        $reply = null;
        if (is_array($keys)) {
            $reply = array();
            foreach ($keys as $key) {
                $reply = $this->_redisClient->watch($keys);
            }
        }
        else {
            $reply = $this->_redisClient->watch($keys);
        }
        return $reply;
    }

    public function multi() {
        $this->initialize();
    }

    public function unwatch() {
        $this->isWatchSupported();
        $this->_redisClient->unwatch();
    }

    public function discard() {
        $this->_redisClient->discard();
        $this->_commands    = array();
        $this->_initialized = false;
        $this->_discarded   = true;
    }

    public function exec() {
        return $this->execute();
    }

    public function execute($block = null) {
        if ($this->_insideBlock === true) {
            throw new Predis_ClientException(
                "Cannot invoke 'execute' or 'exec' inside an active client transaction block"
            );
        }

        if ($block && !is_callable($block)) {
            throw new InvalidArgumentException('Argument passed must be a callable object');
        }

        $blockException = null;
        $returnValues   = array();

        if ($block !== null) {
            $this->setInsideBlock(true);
            try {
                $block($this);
            }
            catch (Predis_CommunicationException $exception) {
                $blockException = $exception;
            }
            catch (Predis_ServerException $exception) {
                $blockException = $exception;
            }
            catch (Exception $exception) {
                $blockException = $exception;
                if ($this->_initialized === true) {
                    $this->discard();
                }
            }
            $this->setInsideBlock(false);
            if ($blockException !== null) {
                throw $blockException;
            }
        }

        if ($this->_initialized === false) {
            return;
        }

        $reply = $this->_redisClient->exec();
        if ($reply === null) {
            throw new Predis_AbortedMultiExec('The current transaction has been aborted by the server');
        }

        $execReply = $reply instanceof Iterator ? iterator_to_array($reply) : $reply;
        $commands  = &$this->_commands;
        $sizeofReplies = count($execReply);

        if ($sizeofReplies !== count($commands)) {
            $this->malformedServerResponse('Unexpected number of responses for a Predis_MultiExecBlock');
        }

        for ($i = 0; $i < $sizeofReplies; $i++) {
            $returnValues[] = $commands[$i]->parseResponse($execReply[$i] instanceof Iterator
                ? iterator_to_array($execReply[$i])
                : $execReply[$i]
            );
            unset($commands[$i]);
        }

        return $returnValues;
    }
}

?>
