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

    public function before() {

        parent::before();

        //init translations
        $this->dict = Model_Dict::getInstance($this->redis);
        
        $this->user = Model_User::getInstance($this->redis);
        if (!$this->user->tryLogIn($this->session->get('authkey'))) {
            $this->redirectError('Wygasła sesja użytkownika', 'loginform');
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
            $this->redirectError('Cannot play on inactive account', '/u/menu');
        }

        if ($this->user->setCurrentCharacter($id)) {
            $this->request->redirect('events');
        } else {
            $this->redirectError('Cannot view events of other player', '/u/menu');
        }

    }
    
}

?>