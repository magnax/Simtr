<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Welcome extends Controller_Base_Admin {
    
	public function action_index() {

        $this->template->content = 'Hello and welcome in Fabular Admin ;-)';
        
	}

} // End Welcome
