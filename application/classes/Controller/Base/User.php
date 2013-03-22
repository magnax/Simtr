<?php defined('SYSPATH') or die('No direct script access.');

/**
 * kontroler dla zalogowanych userów
 */
class Controller_Base_User extends Controller_Base_Base {

    public $template = 'templates/user';
    
    /**
     * @var GameTime Object
     * 
     * overall game time object
     */
    protected $game;
    
    /**
     * current time is calculated once and used later
     * 
     * @var int
     */
    protected $raw_time;


    /**
     *
     * @var User Object
     */
    protected $user = null;

    /**
     * current character
     * 
     * @var Character object
     */
    protected $character = null;

    /**
     * has logged in user admin role?
     * 
     * @var boolean
     */
    protected $is_admin = false;

    /**
     * dictionary
     * @var Object
     */
    protected $dict = null;
    
    /**
     * Location Names object
     * @var Object
     */
    protected $lnames = null;

    public function before() {

        parent::before();

        /**
         * check time daemon existence and get current game time
         */
        try {
            $this->game = new Model_GameTime(Kohana::$config->load('general.paths.time_daemon_path'));    
            $this->raw_time = $this->game->getRawTime();
        } catch (Exception $e) {
            $this->redirectError($e->getMessage());
        }

        $this->template->set_global('current_time', $this->game->getTime());
        $this->template->set_global('current_date', $this->game->getDate());
        
        //init translations
        $this->dict = Model_Dict::getInstance($this->redis);
        
        $this->user = Auth::instance()->get_user();
        if (!$this->user) {
            $this->redirect('login');
        }
        
        $this->is_admin = ($this->user->has('roles', ORM::factory('Role', array('name' => 'admin'))));
        
        $this->template->user = $this->user;
        $this->template->is_admin = $this->is_admin;
        
        $this->template->set('header', View::factory('common/header'));
        $this->template->set('game_info_header', View::factory('common/game_info_header')
            ->set('stats', View::factory('common/stats'))
            ->set('user_menu', View::factory('user/menu')
                ->bind('is_admin', $this->is_admin)
            )
        );
        
    }
    
}

?>