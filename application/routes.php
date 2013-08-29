<?php

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

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

Route::set('activate', 'activate')
	->defaults(array(
		'controller' => 'login',
		'action'     => 'activate'
	));

//default admin routes
Route::set('admin_expanded', 'admin(/<controller>(/<action>(/<id>(/<param>))))')
	->defaults(array(
        'directory'  => 'admin',
		'controller' => 'menu',
		'action'     => 'index'
	));
Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))', array('id'=>'.*'))
	->defaults(array(
        'directory'  => 'admin',
		'controller' => 'menu',
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
		'controller' => 'welcome',
		'action'     => 'index',
	)); 

?>
