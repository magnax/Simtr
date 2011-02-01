<?php defined('SYSPATH') or die('No direct script access.');

//-- Environment setup --------------------------------------------------------

/**
 * Set the default time zone.
 *
 * @see  http://docs.kohanaphp.com/about.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set('America/Chicago');

/**
 * Set the default locale.
 *
 * @see  http://docs.kohanaphp.com/about.configuration
 * @see  http://php.net/setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://docs.kohanaphp.com/about.autoloading
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

//-- Configuration and initialization -----------------------------------------

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
Kohana::init(array('base_url' => 'http://simtr2.mn'));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Kohana_Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Kohana_Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
    'redis'         => APPPATH.'modules/predis'
	// 'auth'       => MODPATH.'auth',       // Basic authentication
	// 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
	// 'database'   => MODPATH.'database',   // Database access
	// 'image'      => MODPATH.'image',      // Image manipulation
	// 'orm'        => MODPATH.'orm',        // Object Relationship Mapping
	// 'pagination' => MODPATH.'pagination', // Paging of results
	// 'userguide'  => MODPATH.'userguide',  // User guide and API documentation
	));

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
Route::set('objects', 'objects') // strona projektów
	->defaults(array(
        'directory'  => 'user',
		'controller' => 'location',
		'action'     => 'objects'
	));
Route::set('inventory', 'inventory(/<type>)') // strona projektów
	->defaults(array(
        'directory'  => 'user',
		'controller' => 'inventory',
		'action'     => 'index',
        'type'       => 'raws'
	));
Route::set('projects', 'projects') // strona projektów
	->defaults(array(
        'directory'  => 'user',
		'controller' => 'project',
		'action'     => 'index'
	));
Route::set('location', 'location') // strona bieżącej lokacji
	->defaults(array(
        'directory'  => 'user',
		'controller' => 'location',
		'action'     => 'index'
	));
Route::set('events', 'events') // strona zdarzeń bież. postaci
	->defaults(array(
        'directory'  => 'user',
		'controller' => 'event',
		'action'     => 'index'
	));
Route::set('logout', 'logout')
	->defaults(array(
        'directory'  => 'user',
		'controller' => 'menu',
		'action'     => 'logout'
	));
Route::set('login', 'login')
	->defaults(array(
        'directory'  => 'guest',
		'controller' => 'login',
		'action'     => 'loginform'
	));
Route::set('loginform', 'loginform')
	->defaults(array(
        'directory'  => 'guest',
		'controller' => 'login',
		'action'     => 'loginform'
	));
Route::set('checklogin', 'check_login')
	->defaults(array(
        'directory'  => 'guest',
		'controller' => 'login',
		'action'     => 'checklogin'
	));
Route::set('error', 'error')
	->defaults(array(
        'directory'  => 'base',
		'controller' => 'error',
		'action'     => 'index'
	));
Route::set('register', 'register')
	->defaults(array(
        'directory'  => 'guest',
		'controller' => 'login',
		'action'     => 'register',
	));
Route::set('registerform', 'registerform')
	->defaults(array(
        'directory'  => 'guest',
		'controller' => 'login',
		'action'     => 'registerform',
	));
Route::set('register_continue', 'register_continue')
	->defaults(array(
        'directory'  => 'guest',
		'controller' => 'login',
		'action'     => 'registerform',
	));
Route::set('check_user', 'check_user')
	->defaults(array(
        'directory'  => 'guest',
		'controller' => 'login',
		'action'     => 'checkuser',
	));
Route::set('character_default', 'char/<id>')
	->defaults(array(
        'directory'  => 'base',
		'controller' => 'user',
		'action'     => 'set'
	));
Route::set('user_default', 'u/<controller>(/<action>(/<id>))')
	->defaults(array(
        'directory'  => 'user',
		'controller' => 'menu',
		'action'     => 'index',
	));
Route::set('admin_default', 'admin/<controller>(/<action>(/<id>))')
	->defaults(array(
        'directory'  => 'admin',
		'controller' => 'menu',
		'action'     => 'index',
	));
Route::set('default', '(<controller>(/<action>(/<id>)))')
	->defaults(array(
        'directory'  => 'guest',
		'controller' => 'welcome',
		'action'     => 'index',
	));

/**
 * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
 * If no source is specified, the URI will be automatically detected.
 */
echo Request::instance()
	->execute()
	->send_headers()
	->response;
