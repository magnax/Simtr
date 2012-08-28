<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Error extends Controller_Template {
    
    public $template = 'templates/error';
    
    public function action_index() {
        $this->template->error_message = Session::instance()->get_once('err');
    }
    
}

?>
