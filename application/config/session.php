<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'native' => array(
        'name' => 'simtr_session',
        'lifetime' => 0,
    ),
    'cookie' => array(
        'name' => 'simtr_session',
        'encrypted' => TRUE,
        'lifetime' => 0,
    ),
    'database' => array(
        'name' => 'simtr_session',
        'encrypted' => TRUE,
        'lifetime' => 0,
        'group' => 'default',
        'table' => 'session',
        'columns' => array(
            'session_id'  => 'session_id',
            'last_active' => 'last_active',
            'contents'    => 'contents'
        ),
        'gc' => 500,
    ),
);

?>
