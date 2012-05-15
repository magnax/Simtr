<?php

class Predis_Client {
    private $_options, $_connection, $_serverProfile, $_responseReader;

    public function __construct($parameters = null, $clientOptions = null) {
        $this->setupClient($clientOptions !== null ? $clientOptions : new Predis_ClientOptions());
        $this->setupConnection($parameters);
    }

    public static function create(/* arguments */) {
        $argv = func_get_args();
        $argc = func_num_args();

        $options = null;
        $lastArg = $argv[$argc-1];
        if ($argc > 0 && !is_string($lastArg) && ($lastArg instanceof Predis_ClientOptions ||
            is_subclass_of($lastArg, 'Predis_RedisServerProfile'))) {
            $options = array_pop($argv);
            $argc--;
        }

        if ($argc === 0) {
            throw new Predis_ClientException('Missing connection parameters');
        }

        return new Predis_Client($argc === 1 ? $argv[0] : $argv, $options);
    }

    private static function filterClientOptions($options) {
        if ($options instanceof Predis_ClientOptions) {
            return $options;
        }
        if (is_array($options)) {
            return new Predis_ClientOptions($options);
        }
        if ($options instanceof Predis_RedisServerProfile) {
            return new Predis_ClientOptions(array(
                'profile' => $options
            ));
        }
        if (is_string($options)) {
            return new Predis_ClientOptions(array(
                'profile' => Predis_RedisServerProfile::get($options)
            ));
        }
        throw new InvalidArgumentException("Invalid type for client options");
    }

    private function setupClient($options) {
        $this->_responseReader = new Predis_ResponseReader();
        $this->_options = self::filterClientOptions($options);

        $this->setProfile($this->_options->profile);
        if ($this->_options->iterable_multibulk === true) {
            $this->_responseReader->setHandler(
                Predis_Protocol::PREFIX_MULTI_BULK,
                new Predis_ResponseMultiBulkStreamHandler()
            );
        }
        if ($this->_options->throw_on_error === false) {
            $this->_responseReader->setHandler(
                Predis_Protocol::PREFIX_ERROR,
                new Predis_ResponseErrorSilentHandler()
            );
        }
    }

    private function setupConnection($parameters) {
        if ($parameters !== null && !(is_array($parameters) || is_string($parameters))) {
            throw new Predis_ClientException('Invalid parameters type (array or string expected)');
        }

        if (is_array($parameters) && isset($parameters[0])) {
            $cluster = new Predis_ConnectionCluster($this->_options->key_distribution);
            foreach ($parameters as $shardParams) {
                $cluster->add($this->createConnection($shardParams));
            }
            $this->setConnection($cluster);
        }
        else {
            $this->setConnection($this->createConnection($parameters));
        }
    }

    private function createConnection($parameters) {
        $params     = $parameters instanceof Predis_ConnectionParameters
                          ? $parameters
                          : new Predis_ConnectionParameters($parameters);
        $connection = new Predis_Connection($params, $this->_responseReader);

        if ($params->password !== null) {
            $connection->pushInitCommand($this->createCommand(
                'auth', array($params->password)
            ));
        }
        if ($params->database !== null) {
            $connection->pushInitCommand($this->createCommand(
                'select', array($params->database)
            ));
        }

        return $connection;
    }

    private function setConnection(Predis_IConnection $connection) {
        $this->_connection = $connection;
    }

    public function setProfile($serverProfile) {
        if (!($serverProfile instanceof Predis_RedisServerProfile || is_string($serverProfile))) {
            throw new InvalidArgumentException(
                "Invalid type for server profile, Predis_RedisServerProfile or string expected"
            );
        }
        $this->_serverProfile = (is_string($serverProfile)
            ? Predis_RedisServerProfile::get($serverProfile)
            : $serverProfile
        );
    }

    public function getProfile() {
        return $this->_serverProfile;
    }

    public function getResponseReader() {
        return $this->_responseReader;
    }

    public function getClientFor($connectionAlias) {
        if (!Predis_Shared_Utils::isCluster($this->_connection)) {
            throw new Predis_ClientException(
                'This method is supported only when the client is connected to a cluster of connections'
            );
        }

        $connection = $this->_connection->getConnectionById($connectionAlias);
        if ($connection === null) {
            throw new InvalidArgumentException(
                "Invalid connection alias: '$connectionAlias'"
            );
        }

        $newClient = new Predis_Client();
        $newClient->setupClient($this->_options);
        $newClient->setConnection($connection);
        return $newClient;
    }

    public function connect() {
        $this->_connection->connect();
    }

    public function disconnect() {
        $this->_connection->disconnect();
    }

    public function isConnected() {
        return $this->_connection->isConnected();
    }

    public function getConnection($id = null) {
        if (!isset($id)) {
            return $this->_connection;
        }
        else {
            return Predis_Shared_Utils::isCluster($this->_connection)
                ? $this->_connection->getConnectionById($id)
                : $this->_connection;
        }
    }

    public function __call($method, $arguments) {
        $command = $this->_serverProfile->createCommand($method, $arguments);
        return $this->_connection->executeCommand($command);
    }

    public function createCommand($method, $arguments = array()) {
        return $this->_serverProfile->createCommand($method, $arguments);
    }

    public function executeCommand(Predis_Command $command) {
        return $this->_connection->executeCommand($command);
    }

    public function executeCommandOnShards(Predis_Command $command) {
        $replies = array();
        if (Predis_Shared_Utils::isCluster($this->_connection)) {
            foreach($this->_connection as $connection) {
                $replies[] = $connection->executeCommand($command);
            }
        }
        else {
            $replies[] = $this->_connection->executeCommand($command);
        }
        return $replies;
    }

    public function rawCommand($rawCommandData, $closesConnection = false) {
        if (Predis_Shared_Utils::isCluster($this->_connection)) {
            throw new Predis_ClientException('Cannot send raw commands when connected to a cluster of Redis servers');
        }
        return $this->_connection->rawCommand($rawCommandData, $closesConnection);
    }

    private function sharedInitializer($argv, $initializer) {
        $argc = count($argv);
        if ($argc === 0) {
            return $this->$initializer();
        }
        else if ($argc === 1) {
            list($arg0) = $argv;
            return is_array($arg0) ? $this->$initializer($arg0) : $this->$initializer(null, $arg0);
        }
        else if ($argc === 2) {
            list($arg0, $arg1) = $argv;
            return $this->$initializer($arg0, $arg1);
        }
        return $this->$initializer($this, $arguments);
    }

    public function pipeline(/* arguments */) {
        $args = func_get_args();
        return $this->sharedInitializer($args, 'initPipeline');
    }

    public function pipelineSafe($pipelineBlock = null) {
        return $this->initPipeline(array('safe' => true), $pipelineBlock);
    }

    private function initPipeline(Array $options = null, $pipelineBlock = null) {
        $pipeline = null;
        if (isset($options)) {
            if (isset($options['safe']) && $options['safe'] == true) {
                $connection = $this->getConnection();
                $pipeline   = new Predis_CommandPipeline($this, $connection instanceof Predis_Connection
                    ? new Predis_Pipeline_SafeExecutor($connection)
                    : new Predis_Pipeline_SafeClusterExecutor($connection)
                );
            }
            else {
                $pipeline = new Predis_CommandPipeline($this);
            }
        }
        else {
            $pipeline = new Predis_CommandPipeline($this);
        }
        return $this->pipelineExecute($pipeline, $pipelineBlock);
    }

    private function pipelineExecute(Predis_CommandPipeline $pipeline, $block) {
        return $block !== null ? $pipeline->execute($block) : $pipeline;
    }

    public function multiExec(/* arguments */) {
        $args = func_get_args();
        return $this->sharedInitializer($args, 'initMultiExec');
    }

    private function initMultiExec(Array $options = null, $transBlock = null) {
        $multi = isset($options) ? new Predis_MultiExecBlock($this, $options) : new Predis_MultiExecBlock($this);
        return $transBlock !== null ? $multi->execute($transBlock) : $multi;
    }

    public function pubSubContext() {
        return new Predis_PubSubContext($this);
    }
}

?>
