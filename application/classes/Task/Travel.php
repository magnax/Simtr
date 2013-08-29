<?php defined('SYSPATH') or die('No direct script access.');

class Task_Travel extends Minion_Daemon {
    
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
        
        //get all travelling characters       
        $travelling = Model_Character::getTravellingIds();
        
        foreach ($travelling as $character_id) {
            
            $character = new Model_Character($character_id);
            $position = $character->get_position_object();
            
            echo $character->name . '; ' . "\n";
            echo "Position found: {$position->id}" . "\n";
            
            //$progress = $position->get_progress();
            
            //echo "Progress: $progress \n";
            
            //print_r($position);
            
            //$position_from_id = 2 / $position->dest;
            
            //$from_location_column = "location_{$position_from_id}_id";
            $from_location_column = 'location_' . (2/$position->dest). '_id';
            $to_location_column = "location_{$position->dest}_id";
            
            //echo "from: ==$from_location_column==to: $to_location_column==";
            
            $road = $character->get_road();
            $from_location = $road->$from_location_column;
            $to_location = $road->$to_location_column;
            
            echo "Travelling on road #: {$road->id} from: $from_location to: $to_location \n";
            
            $position->move($time);
            $position->save();
            
            $new_progress = $position->get_progress();
            
            if ($new_progress >= 1) {
                
                $character->set_location($to_location);
                
                //wysłanie eventu
                $event = new Model_Event();
                $event->type = Model_Event::ARRIVE_INFO;
                $event->date = $this->game->getRawTime();

                $event->add('params', array('name' => 'sndr', 'value' => $character->id));
                $event->add('params', array('name' => 'location_id', 'value' => $to_location));
                $event->add('params', array('name' => 'from_location_id', 'value' => $from_location));

                $event->save();

                $dest_location = new Model_Location($to_location);
                
                $event->notify($dest_location->getVisibleCharacters());
                
                echo "Destination reached! \n";
                
            } else {
                
                echo "New progress: $new_progress \n";
                
            }
            
            unset(
                $position, $new_progress, 
                $dest_location, $from_location, $to_location, 
                $event, $character, $road
            );
            
        }
        
		// This will be continuously executed
		$this->_log(Log::INFO, "Executing: $time");

		return TRUE;
        
        //end loop immediately for testing purpose
        //return false;
        
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