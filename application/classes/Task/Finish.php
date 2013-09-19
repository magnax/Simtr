<?php defined('SYSPATH') or die('No direct script access.');

class Task_Finish extends Minion_Daemon {
    
    /**
	 * @var array Minion config options.  Merged with Minion_Daemon options
	 * @access protected
	 */
	protected $_config = array();

	/**
	 * @var int How long to sleep for, in ms, between iterations
	 * @access protected
	 */
	protected $_sleep = 2000000; // 2 seconds

    /**
     * @var GameTime Object 
     */
    protected $game = null;
    
    /**
     * @var RedisDB Object Redis database object
     */
    protected $redis = null;

    /**
	 * Setup tasks
	 * 
	 * @access public
	 * @param array $config
	 * @return void
	 */
	public function before(array $config)
	{
		// Handle any setup tasks
		$this->_log(Log::INFO, "Starting...");
        
        /**
         * set game object and try to get time
         */
        try {
            $this->game = new Model_GameTime(Kohana::$config->load('general.paths.time_daemon_path'));    
            $time = $this->game->getRawTime();
        } catch (Exception $e) {
            $this->_log(Log::ERROR, $e->getMessage());
            return false;
        }
        
        /**
         * set RedisDB object
         */
        $this->redis = RedisDB::instance();
        
	}

	/**
	 * Main loop.
	 * Return FALSE or set $this->_terminate = TRUE to break out of the loop
	 * 
	 * @access public
	 * @param array $config
	 * @return boolean
	 */
	public function loop(array $config)
	{
        
        try {   
            $time = $this->game->getRawTime();
        } catch (Exception $e) {
            $this->_log(Log::ERROR, $e->getMessage());
            return false;
        }

        $projects_ids = Model_Project::get_finished_projects_ids();
        foreach ($projects_ids as $project_id) {
            echo $project_id . "\n";
            $project = Model_Project::factory(NULL, $project_id);
            echo $project->type_id . "\n";
            $owner = new Model_Character($project->owner_id);
            $location = new Model_Location($project->location_id);
            echo 'Owner: ' . $owner->name . "\n";
            echo 'Location: ' . $location->name . "\n";
            $is_owner_present = ($project->location_id == $owner->location_id);
            echo 'Owner present: ' . (int) $is_owner_present . "\n";
            $event_type = $project->settle($owner, $location);
            echo 'Event type: ' . $event_type . "\n";
            $workers = $project->get_workers();
            print_r($workers);
            if (count($workers)) {
                foreach($workers as $worker_id) {
                    //usuń pracownika
                    RedisDB::del("characters:$worker_id:current_project");
                }                
                //$project->remove_all_workers();
            }
            if ($is_owner_present && !in_array($project->owner_id, $workers)) {
                array_push($workers, $project->owner_id);
            }
            
            $project->remove_all();
            
            //wysłanie eventu
            $event = new Model_Event();
            $event->type = $event_type;
            $event->date = $time;

            $event->add('params', array('name' => 'sndr', 'value' => $project->owner_id));
            
            $event = $project->add_event_params($event);

            $event->save();

            $event->notify($workers);
            
            print_r($event);
                
        }
        
		// This will be continuously executed
		$this->_log(Log::INFO, "Executing: $time");

		return TRUE;
        
        //end loop immediately for testing purpose
//        return false;
        
	}

	/**
	 * Tear down tasks
	 * 
	 * @access public
	 * @param array $config
	 * @return void
	 */
	public function after(array $config)
	{
		// Handle any cleanup tasks
		$this->_log(Log::INFO, "Ending...");
	}
    
}

?>

