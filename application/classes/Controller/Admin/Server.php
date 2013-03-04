<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Server extends Controller_Base_Admin {

    public function action_index() {
    }
    
    public function action_on() {
        $socket_io_path = Kohana::$config->load('general.socket_io_path');
        exec("node $socket_io_path &", $output);
        $this->template->content = join('<br>', $output);
    }
    
    public function action_check() {
        $output = shell_exec('ps aux | grep node');
        $this->template->content = $output;
    }
    
}

?>
