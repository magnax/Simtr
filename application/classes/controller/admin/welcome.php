<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Welcome extends Controller_Base_Base {

    public $template = 'templates/guest'; 
    
	public function action_index() {

        echo 'Hello and welcome';
        
	}

} // End Welcome
