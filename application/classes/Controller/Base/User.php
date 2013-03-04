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

        $this->template->current_time = $this->game->getTime();
        $this->template->current_date = $this->game->getDate();
        
        //init translations
        $this->dict = Model_Dict::getInstance($this->redis);
        
        $this->user = Auth::instance()->get_user();
        if (!$this->user) {
            Request::current()->redirect('login');
        }
        
        $this->is_admin = ($this->user->has('roles', ORM::factory('Role', array('name' => 'admin'))));
        
        $this->template->user = $this->user;
        $this->template->is_admin = $this->is_admin;
        
    }

    /**
     * ustawia bieżącą postać - wszystkie akcje będą dotyczyły właśnie
     * tej postaci
     *
     * @param <type> $id IDCharacter
     */
    public function action_set($id) {
        
        if (!$this->user->isActive()) {
            $this->redirectError('Cannot play on inactive account', 'user/menu');
        }

        if ($this->user->setCurrentCharacter($id)) {
            $this->redirect('user/event');
        } else {
            $this->redirectError('Cannot view events of other player', 'user/menu');
        }

    }
    
    public function action_logout() {

        if ($this->user->logout()) {
            $this->session->delete('authkey');
            $this->redirect('/');
        }

    }
    
}

?>