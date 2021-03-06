<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH . 'classes/Kohana/Core' . EXT;

if (is_file(APPPATH . 'classes/Kohana' . EXT)) {
    // Application extends the core
    require APPPATH . 'classes/Kohana' . EXT;
} else {
    // Load empty core extension
    require SYSPATH . 'classes/Kohana' . EXT;
}

/**
 * Set the default time zone.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set('Europe/Warsaw');

/**
 * Set the default locale.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/setlocale
 */
setlocale(LC_ALL, 'pl_PL.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://kohanaframework.org/guide/using.autoloading
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('en-us');

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 * 
 * Default environment is DEVELOPMENT
 * 
 */
$env = getenv('KOHANA_ENV');

if ($env  && defined('Kohana::'.strtoupper($env))) {
    
	Kohana::$environment = constant('Kohana::'.strtoupper($env));
    
} else {
    
    Kohana::$environment = Kohana::DEVELOPMENT;
    
}

/**
 * cookie salt for remember login
 */
Cookie::$salt = 'whatever you want';

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 */
Kohana::init(array(
	'base_url'   => '',
    'profile'   => false,
    'index_file' => false,
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */

Kohana::$config->attach(new Config_File);
 
if (Kohana::$environment === Kohana::TESTING) {
    Kohana::$config->attach(new Config_File('config/testing'), true);
}

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
    'email'         => APPPATH.'modules/email',     //email module
    //'unittest'      => MODPATH.'unittest',          // Unit testing
    'redisent'      => APPPATH.'modules/redisent',  // redisent library
	'orm'           => MODPATH.'orm',               // Object Relationship Mapping
	'auth'          => MODPATH.'auth',              // Auth module
	'database'      => MODPATH.'database',          // Database
    'cache'         => MODPATH.'cache',             //cache module
    'ohm'           => APPPATH.'modules/ohm',       // object-hash mapping redis library
    'elephant'      => APPPATH.'modules/elephant',  // ElephantIOClient socket.io implementation
    'minion'        => MODPATH.'minion',            // CLI Tasks
));

// Load the routes
require APPPATH . 'routes' . EXT;
