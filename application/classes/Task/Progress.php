<?php defined('SYSPATH') or die('No direct script access.');

class Task_Progress extends Minion_Daemon {
    
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

        $projects_ids = Model_Project::get_active_projects_ids();
        foreach ($projects_ids as $project_id) {
            echo $project_id . "\n";
            $project = Model_Project::factory(NULL, $project_id);
            print_r($project->as_array());
            
            $participants = $project->get_participants();
            print_r($participants);
            
            $elapsed = 0;
            
            foreach ($participants as $p) {
                if ($p['end']) {
                    $elapsed += ($p['end'] - $p['start']) * $p['factor'];
                } else {
                    $elapsed += ($time - $p['start']) * $p['factor'];
                }
            }
             
            if ($elapsed >= $project->time) {
                
                $project->time_elapsed = $project->time;
                //dodaj projekt do rozliczenia i usuń z aktywnych
                $project->finish();
                
            } else {
                
                $project->time_elapsed = $elapsed;
                $project->save();
                
            }

        }
        
		// This will be continuously executed
		$this->_log(Log::INFO, "Executing: $time");

		return TRUE;
//        
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
