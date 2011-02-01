<?php

class Predis_ConnectionParameters {
    const DEFAULT_HOST = '127.0.0.1';
    const DEFAULT_PORT = 6379;
    const DEFAULT_TIMEOUT = 5;
    private $_parameters;

    public function __construct($parameters = null) {
        $parameters = $parameters !== null ? $parameters : array();
        $this->_parameters = is_array($parameters)
            ? self::filterConnectionParams($parameters)
            : self::parseURI($parameters);
    }

    private static function parseURI($uri) {
        $parsed = @parse_url($uri);

        if ($parsed == false || $parsed['scheme'] != 'redis' || $parsed['host'] == null) {
            throw new Predis_ClientException("Invalid URI: $uri");
        }

        if (array_key_exists('query', $parsed)) {
            $details = array();
            foreach (explode('&', $parsed['query']) as $kv) {
                list($k, $v) = explode('=', $kv);
                switch ($k) {
                    case 'database':
                        $details['database'] = $v;
                        break;
                    case 'password':
                        $details['password'] = $v;
                        break;
                    case 'connection_async':
                        $details['connection_async'] = $v;
                        break;
                    case 'connection_persistent':
                        $details['connection_persistent'] = $v;
                        break;
                    case 'connection_timeout':
                        $details['connection_timeout'] = $v;
                        break;
                    case 'read_write_timeout':
                        $details['read_write_timeout'] = $v;
                        break;
                    case 'alias':
                        $details['alias'] = $v;
                        break;
                    case 'weight':
                        $details['weight'] = $v;
                        break;
                }
            }
            $parsed = array_merge($parsed, $details);
        }

        return self::filterConnectionParams($parsed);
    }

    private static function getParamOrDefault(Array $parameters, $param, $default = null) {
        return array_key_exists($param, $parameters) ? $parameters[$param] : $default;
    }

    private static function filterConnectionParams($parameters) {
        return array(
            'host' => self::getParamOrDefault($parameters, 'host', self::DEFAULT_HOST),
            'port' => (int) self::getParamOrDefault($parameters, 'port', self::DEFAULT_PORT),
            'database' => self::getParamOrDefault($parameters, 'database'),
            'password' => self::getParamOrDefault($parameters, 'password'),
            'connection_async'   => self::getParamOrDefault($parameters, 'connection_async', false),
            'connection_persistent' => self::getParamOrDefault($parameters, 'connection_persistent', false),
            'connection_timeout' => self::getParamOrDefault($parameters, 'connection_timeout', self::DEFAULT_TIMEOUT),
            'read_write_timeout' => self::getParamOrDefault($parameters, 'read_write_timeout'),
            'alias'  => self::getParamOrDefault($parameters, 'alias'),
            'weight' => self::getParamOrDefault($parameters, 'weight'),
        );
    }

    public function __get($parameter) {
        return $this->_parameters[$parameter];
    }

    public function __isset($parameter) {
        return isset($this->_parameters[$parameter]);
    }
}

?>
