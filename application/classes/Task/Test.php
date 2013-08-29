<?php defined('SYSPATH') or die('No direct script access.');

class Task_Test extends Minion_Daemon {
    
    /**
	 * @var array Minion config options.  Merged with Minion_Daemon options
	 * @access protected
	 */
	protected $_config = array();

	/**
	 * @var int How long to sleep for, in ms, between iterations
	 * @access protected
	 */
	protected $_sleep = 3000000; // 3 seconds

    protected $game = null;
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
        
        try {
            $this->game = new Model_GameTime(Kohana::$config->load('general.paths.time_daemon_path'));    
            $time = $this->game->getRawTime();
        } catch (Exception $e) {
            $this->_log(Log::ERROR, $e->getMessage());
            return false;
        }
        
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
        
		// This will be continuously executed
		$this->_log(Log::INFO, "Executing: $time");

		return TRUE;
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
