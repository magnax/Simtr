<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Lock extends Controller_Base_Character {
    
    public function action_lock() {
        
        if ($this->request->param('lock_nr')) {
            $lock = ORM::factory('Lock')
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
        
        $this->redirect('user/location/objects');
        
    }
    
    /**
     * lock upgrade
     */
    public function action_upgrade() {
        
        //one can upgrade only when inside locked entity, so get current lock
        $lock = $this->location->lock;
        
        if (!$lock->loaded()) {
            //location has not lock yet
            $lock = new Model_Lock();
        }
        
        //lock upgrade is possible only when one has proper key
        if ($this->character->hasKey($lock->nr) || !$lock->id) {
        
            if (HTTP_Request::POST == $this->request->method()) {

                $locktype = ORM::factory('LockType')
                    ->where('level', '=', $this->request->post('level'))
                    ->find();
                                
                $spec = ORM::factory('Spec')
                    ->where('itemtype_id', '=', $locktype->itemtype_id)
                    ->find();
                
                $raws = Model_Spec_Raw::getRaws($locktype->itemtype_id);
                
                $key = Model_Project::TYPE_LOCKBUILD;
                
                $project_manager = Model_ProjectManager::getInstance(
                    Model_Project::getInstance($key, $this->redis)//;
                );

                $data = array(
                    'name'=>'Wstawianie zamka: '.$spec->item->name,
                    'owner_id'=>$this->character->id,
                    'amount'=>1,
                    'time'=>$spec->time,
                    'type_id'=>$key,
                    'place_type'=>$this->location->locationtype_id,
                    'place_id'=>$this->location->id,
                    'itemtype_id'=>$spec->itemtype_id,
                    'created_at'=>$this->game->getRawTime()
                );

                $project_manager->set($data);
                $project_manager->save();

                $this->location->addProject($project_manager->getId(), $this->redis);

                foreach ($raws as $raw) {

                    $project_raw = new Model_Project_Raw();
                    $project_raw->project_id = $project_manager->getId();
                    $project_raw->resource_id = $raw->resource_id;
                    $project_raw->amount = 0;

                    $project_raw->save();

                }
                
                $this->redirect('events');
                
            }
            
            if ($lock->id) {
                $current_level = $lock->locktype->level;
            } else {
                //no lock, so all levels possible
                $current_level = 0;
            }
            
            //show view with upgrade options
            $levels = Model_LockType::getUpgradeLevels($current_level);
            
            $this->view->specs = array();
            
            foreach ($levels as $level) {
                
                $specs = ORM::factory('Spec')
                    ->where('itemtype_id', '=', $level->itemtype_id)
                    ->find();
                
                if ($specs->loaded()) {
                    $raws = Model_Spec_Raw::getRaws($level->itemtype_id);

                    $this->view->specs[$level->itemtype_id] = array(
                        'specs' => $specs,
                        'raws' => $raws,
                    );
                }
            }
            
            $this->view->lock = $lock;
            $this->view->levels = $levels;
            
        } else {
            Session::instance()->set('error', 'Nie masz klucza!');
        }
        
    }
    
}

?>
