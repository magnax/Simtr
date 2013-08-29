<?php defined('SYSPATH') or die('No direct script access.');

class Task_Echo extends Minion_Task {

    protected $_options = array(
        
    );

    protected function _execute(array $params) {
        
        echo 'All OK! Minion is working.';
        
    }
    
}

?>
