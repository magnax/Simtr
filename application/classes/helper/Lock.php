<?php defined('SYSPATH') or die('No direct script access.');

class Helper_Lock {
    
    public static function show_lock_status(Model_Lock $lock) {
        echo ($lock->locked) ? 'zamknięte' : 'otwarte';
    }
 
    public static function show_lock_action(Model_Lock $lock, $has_key) {   
        if ($has_key) {
            echo ($lock->locked)? HTML::anchor('unlock','[otwórz]'):HTML::anchor('lock', '[zamknij]');
        }
    }
    
    public static function show_lock_upgrade(Model_Lock $lock, $can_upgrade) {
        if ($can_upgrade) {
            $output = ($lock->locktype->level > 0) ? '[ulepsz zamek]' : '[wstaw zamek]';
            echo HTML::anchor('lock/upgrade', $output);
        }
    }
    
    public static function show_lock_level(Model_Lock $lock) {
        if ($lock->locktype->level == 0) {
            echo 'brak';
        } else {
            echo $lock->locktype->level . ' (nr: ' . $lock->nr . ')'; 
        }
    }
    
} 

?>
