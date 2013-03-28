<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'default' => array(
        'driver' => 'Redisent',
        'dsn' => 'redis://localhost:6379',
        'database' => 1,
    )
);

?>