<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Lock extends Controller_Base_Character {
    
    public function action_lock() {
        
        if ($this->request->param('lock_nr')) {
            $lock = ORM::factory('lock')
                ->where('nr', '=', $this->request->param('lock_nr'))
                ->find();
        } else {
            $lock = $this->location->lock;
        }
        
        if (!$lock->loaded()) {
            Session::instance()->set('error', 'Bad lock!');
        }
        
        if ($this->character->hasKey($lock->nr)) {
            $lock->locked = !$lock->locked;
            $lock->save();
        } else {
            Session::instance()->set('error', 'Nie masz klucza!');
        }
        
        $this->request->redirect('user/location/objects');
        
    }
    
    /**
     * lock upgrade
     */
    public function action_upgrade() {
        
        //one can upgrade only when inside locked entity, so get current lock
        $lock = $this->location->lock;
        
        if (!$lock->loaded()) {
            Session::instance()->set('error', 'Bad lock!');
        }
        
        //lock upgrade is possible only when one has proper key
        if ($this->character->hasKey($lock->nr)) {
            
            //show view with upgrade options
            $levels = Model_Locktype::getUpgradeLevels($lock->level);
            
//            $level = new stdClass();
//            $level->level = 2;
//            $level->name = 'żelazny zamek';
//            
//            $levels[] = $level;
//            
//            $level = new stdClass();
//            $level->level = 3;
//            $level->name = 'stalowy zamek';
//            
//            $levels[] = $level;
//            
//            $level = new stdClass();
//            $level->level = 4;
//            $level->name = 'pancerny zamek';
//            
//            $levels[] = $level;
            
            $this->view->lock = $lock;
            $this->view->levels = $levels;
            
        } else {
            Session::instance()->set('error', 'Nie masz klucza!');
        }
        
    }
    
}

?>
