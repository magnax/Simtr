<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends Model_Auth_User {
 
    /**
     * extend user class to have another relations in model
     */
    protected function _initialize() {
        
        $this->_has_many['characters'] = array(
            'model' => 'character',
            'foreign_key' => 'user_id',
            'far_key' => 'id'
        );
        
        parent::_initialize();
        
    }

    public function rules() {

        return array(
            'password' => array(
                array('not_empty'),
            ),
            'email' => array(
                array('not_empty'),
                array('email'),
                array(array($this, 'unique'), array('email', ':value')),
            ),
        );

    }

    public static function get_password_validation($values) {

        return Validation::factory($values)
            ->rule('password', 'min_length', array(':value', 6))
            ->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));

    }
    
    public function send_activation_email() {
        
        $message = 'Below is your activation code, click link or copy it and paste
            in browser address field.<br><br>
            <a href="'.Kohana::$config->load('general.site_url').'activate?id='.
            $this->id.'&code='.$this->activation_code.
            '">'.Kohana::$config->load('general.site_url').'index.php/login/activate?id='.
            $this->id.'&code='.$this->activation_code.'</a>';

        $email = Email::factory('Activate your Fabular account')
            ->message($message, 'text/html')
            ->to($this->email)
            ->bcc('magnax@gmail.com')
            ->from('noreply@fabular.pl', 'Fabular.pl')
            ->send();
        
    }

}

?>
