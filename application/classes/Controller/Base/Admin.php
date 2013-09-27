<?php  defined('SYSPATH') or die('No direct script access.');

/**
 * kontroler dla administratorów
 */
class Controller_Base_Admin extends Controller_Base_User {

    public $template = 'templates/admin';
    
    public function before() {
        
        parent::before();
        
        $this->template->bind_global('request', $this->request);
        $this->template->set_global('server_addr', Kohana::$config->load('general.server_addr'));
        
    }
    
}

?>