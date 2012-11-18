<?php

return array(
    'client_class' => 'Redisent',
    'dsn' => 'redis://localhost:6379',
    'default' => array(
        'type' => 'mysql',
        'connection' => array(
          'hostname'   => '127.0.0.1',
          'database'   => 'DBNAME',
          'username'   => 'USERNAME',
          'password'   => 'PASSWORD',
          'persistent' => FALSE,
        ),
        'table_prefix' => '',
        'charset'      => 'utf8',
        'caching'      => FALSE,
        'profiling'    => TRUE,
    )
);

?>
