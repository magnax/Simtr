<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 
 * This is example config for sending emails with gmail SMTP
 * You just rename it to 'email.php' and fill in your own gmail credentials
 * 
 */
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
        'username'=>'your_username_at_gmail_com',
        'password'=>'your_password_at_gmail_com',
        'encryption' => 'tls',
    ),
//    'driver' => 'native',
//    'options' => null,
);