<?php

/**
 * kontroler dla zalogowanych userów
 */
class Controller_Base_User extends Controller_Base_Base {

    public $template = 'templates/user';

    /**
     *
     * @var User Object
     */
    protected $user = null;
    
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

        //init translations
        $this->dict = Model_Dict::getInstance($this->redis);
        
        //init location names
        $this->lnames = Model_LNames::getInstance($this->redis, $this->dict);
        
        $this->user = Model_User::getInstance($this->redis);
        if (!$this->user->tryLogIn($this->session->get('authkey'))) {
            $this->redirectError('Wygasła sesja użytkownika', 'guest/login/loginform');
        }
        $this->user->refreshActive();

        $this->template->user = $this->user;
        
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
            $this->request->redirect('user/event');
        } else {
            $this->redirectError('Cannot view events of other player', 'user/menu');
        }

    }
    
}

?>