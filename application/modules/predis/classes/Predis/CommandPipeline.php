<?php

class Predis_CommandPipeline {
    private $_redisClient, $_pipelineBuffer, $_returnValues, $_running, $_executor;

    public function __construct(Predis_Client $redisClient, Predis_Pipeline_IPipelineExecutor $executor = null) {
        $this->_redisClient    = $redisClient;
        $this->_executor       = $executor !== null ? $executor : new Predis_Pipeline_StandardExecutor();
        $this->_pipelineBuffer = array();
        $this->_returnValues   = array();
    }

    public function __call($method, $arguments) {
        $command = $this->_redisClient->createCommand($method, $arguments);
        $this->recordCommand($command);
        return $this;
    }

    private function recordCommand(Predis_Command $command) {
        $this->_pipelineBuffer[] = $command;
    }

    private function getRecordedCommands() {
        return $this->_pipelineBuffer;
    }

    public function flushPipeline() {
        if (count($this->_pipelineBuffer) > 0) {
            $connection = $this->_redisClient->getConnection();
            $this->_returnValues = array_merge(
                $this->_returnValues,
                $this->_executor->execute($connection, $this->_pipelineBuffer)
            );
            $this->_pipelineBuffer = array();
        }
        return $this;
    }

    private function setRunning($bool) {
        if ($bool == true && $this->_running == true) {
            throw new Predis_ClientException("This pipeline is already opened");
        }
        $this->_running = $bool;
    }

    public function execute($block = null) {
        if ($block && !is_callable($block)) {
            throw new InvalidArgumentException('Argument passed must be a callable object');
        }

        // TODO: do not reuse previously executed pipelines
        $this->setRunning(true);
        $pipelineBlockException = null;

        try {
            if ($block !== null) {
                $block($this);
            }
            $this->flushPipeline();
        }
        catch (Exception $exception) {
            $pipelineBlockException = $exception;
        }

        $this->setRunning(false);

        if ($pipelineBlockException !== null) {
            throw $pipelineBlockException;
        }

        return $this->_returnValues;
    }
}

?>
