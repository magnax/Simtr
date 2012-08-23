<?php defined('SYSPATH') or die('No direct script access.');

/**
 * kontroler dla niezalogowanych userów
 */
class Controller_Base_Guest extends Controller_Base_Base {

    public $template = 'templates/guest';   
    
    public function before() {
        
        parent::before();
        
    }
    
}

?>
