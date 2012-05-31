<?php

/**
 * User class
 */
abstract class Model_User {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    /**
     * pola zapisywane
     */
    protected $id;
    protected $email;
    protected $password;
    protected $firstname;
    protected $lastname;
    protected $birthdate;
    protected $status = self::STATUS_INACTIVE;
    protected $register_date;
    protected $current_character_id;
    protected $current_location_id;
    protected $authkey;

    protected $characters = array(); //zbiór postaci
    /**
     * pola wewnętrzne klasy
     */
    protected $logged_in = false;
    protected $source;

    public function  __construct($source) {
        $this->source = $source;
    }

    public static function getInstance($source) {
        if ($source instanceof Predis_Client) {
            return new Model_User_Redis($source);
        } else {
            return null;
        }
    }

    public function isLoggedIn() {
        return $this->logged_in;
    }    

    public function getID() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRegisterDate() {
        return $this->register_date;
    }    

    public function getFullName() {
        return $this->firstname.' '.$this->lastname;
    }

    public function getBirthYear() {
        return $this->birthdate ? date('Y', $this->birthdate) : '-';
    }

    public function getStatus() {
        return $this->status;
    }
    
    public function isActive() {
        return ($this->status == self::STATUS_ACTIVE);
    }


    public function setStatus($status) {
        $this->status = $status;
    }

    public function getCurrentCharacter() {
        return $this->current_character_id;
    }

    public function getCurrentLocationID() {
        return $this->current_location_id;
    }    

    public function getCharacters() {
        return $this->characters;
    }
    
    public function toArray() {
        return array(
            'id'=>$this->id,
            'email'=>$this->email,
            'status'=>$this->status,
            'firstname'=>$this->firstname,
            'lastname'=>$this->lastname,
            'birthdate'=>$this->birthdate,
            'register_date'=>$this->register_date,
            'characters'=>$this->characters
        );
    }

    public function createNew($post) {
        
        $this->email = $post['email'];
        $this->password = $post['pass'];
        $this->register_date = date("Y-m-d H:i:s");
        $this->characters = array();
        
        $this->save();
        
        return $this;
    }
    
    public function update(array $data) {
        $this->firstname = $data['firstname'];
        $this->lastname = $data['lastname'];
        $this->birthdate = strtotime($data['birthdate']);
        $this->save();
    }

    public abstract function appendCharacter($id);
    public abstract function save();
    public abstract function isDuplicateEmail($email);
    public abstract function login($id, $password);
    public abstract function logout();
    public abstract function fetchActivationCode($id);
    
}

?>
