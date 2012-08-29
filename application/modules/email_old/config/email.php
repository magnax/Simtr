<?php defined('SYSPATH') OR die('No direct access allowed.');
return array(
	/**
	 * SwiftMailer driver, used with the email module.
	 *
	 * Valid drivers are: native, sendmail, smtp
	 */
	'driver' => 'smtp',
    'options' =>  array(
        'hostname'=>'smtp.gmail.com',
        'port'=>'587', //'25' or '465', '587' for gmail,
        'username'=>'magnax@gmail.com',
        'password'=>'reduziert',
        'encryption' => 'tls',
    ),
//    'driver' => 'native',
//    'options' => null,
);