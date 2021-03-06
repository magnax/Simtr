<?php

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

Route::set('info', 'info')
    ->defaults(array(
        'directory' => 'user',
        'controller' => 'char',
        'action' => 'info'
    ));

Route::set('go', 'user/go/<id>', array('id' => '[0-9]*'))
    ->defaults(array(
        'directory' => 'user',
        'controller' => 'go',
        'action' => 'index'
    ));
Route::set('lname_or_chname', '<controller>/<id>', array('controller' => 'chname|lname', 'id' => '[0-9]*'))
    ->defaults(array(
        'controller' => ':controller',
        'action' => 'index'
    ));
/**
 * lock/unlock buildings, vehicles, rooms
 */
Route::set('lock', 'lock(/<lock_nr>)', array('lock_nr' => '[0-9a-f]{4,12}'))
	->defaults(array(
        'directory' => 'user',
		'controller' => 'lock',
		'action'     => 'lock'
	));
Route::set('unlock', 'unlock(/<lock_nr>)', array('lock_nr' => '[0-9a-f]{4,12}'))
	->defaults(array(
        'directory' => 'user',
		'controller' => 'lock',
		'action'     => 'lock'
	));
Route::set('lock_default', 'lock(/<action>)')
	->defaults(array(
        'directory' => 'user',
		'controller' => 'lock',
		'action'     => 'index'
	));

//User management actions
Route::set('user_actions', '<action>', array('action' => 'activate|register|remind'))
	->defaults(array(
		'controller' => 'users',
		'action'     => ':action'
	));

//Session management actions
Route::set('session_actions', '<action>', array('action' => 'login|logout'))
	->defaults(array(
		'controller' => 'sessions',
		'action'     => ':action'
	));

//default admin routes
Route::set('admin_expanded', 'admin(/<controller>(/<action>(/<id>(/<param>))))')
	->defaults(array(
        'directory'  => 'admin',
		'controller' => 'welcome',
		'action'     => 'index'
	));
Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))', array('id'=>'.*'))
	->defaults(array(
        'directory'  => 'admin',
		'controller' => 'welcome',
		'action'     => 'index'
	));

Route::set('events', 'events/p(/<page>)', array('page'=>'.*'))
	->defaults(array(
		'controller' => 'events',
		'action'     => 'index'
	));

Route::set('user_build', 'user/build(/<menu_id>)', array('menu_id'=>'.*'))
	->defaults(array(
        'directory'  => 'user',
		'controller' => 'build',
		'action'     => 'index'
	));

//default user routes
Route::set('user_default', 'user(/<controller>(/<action>(/<id>)))', array('id'=>'.*'))
	->defaults(array(
        'directory'  => 'user',
		'controller' => 'menu',
		'action'     => 'index'
	));
//default route
Route::set('default', '(<controller>(/<action>(/<id>)))', array('id'=>'.*'))
	->defaults(array(
		'controller' => 'static',
		'action'     => 'index',
	)); 

?>
