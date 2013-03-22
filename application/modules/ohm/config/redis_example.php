<?php defined('SYSPATH') or die('No direct script access.');

/**
 * configuration file for connecting to RedisDB
 * uses Redisent driver
 * 
 * change the name (or copy this file) to 'redis.php' and fill your values
 */

return array(
    'default' => array(
        'driver' => 'Redisent',
        'dsn' => 'redis://localhost:6379',
    )
);

?>
