<?php defined('SYSPATH') or die('No direct script access.');

/**
 * bazowy kontroler postaci
 * zawsze musi istnieć bieżąca postać usera
 */
class Controller_Base_Character extends Controller_Base_User {

    public $template = 'templates/character';
    
    /**
     * current character
     */
    protected $character = null;
    
    /**
     * current location
     */
    protected $location;
    
    protected $lang = 'pl';

    public function before() {
        
        parent::before();
        
        $this->character = new Model_Character($this->session->get('current_character'));
        $this->character->setSource($this->redis);
        
        //redirect if no current character
        if (!$this->character) {
            Request::current()->redirect('user/menu');
        }
        
        //redirect if character is dead
        if (!$this->character->life && !($this->request->uri() == 'events')) {            
            $this->redirectError('Jesteś martwy a martwi zazwyczaj nic już nie robią!', 'events');
        }
        
        //redirect if user not activated
        if (!$this->user->active) {
            $this->redirectError('Twoje konto jest nieaktywne!!', 'user');
        }
        
        //set character for all views
        $this->template->set_global('character', $this->character->getInfo($this->raw_time));
        $this->location = new Model_Location($this->character->location_id);        

    }

}

?>
