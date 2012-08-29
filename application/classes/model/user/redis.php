<?php

class Model_User_Redis extends Model_User {

    public function setCurrentCharacter($id) {

        if (in_array($id, $this->characters)) {
            $this->current_character_id = $id;
            $this->source->set("users:{$this->id}:current_character", $id);
            return true;
        } else {
            return false;
        }

    }

    //przypisuje nowo utworzoną postać do konta użytkownika
    public function appendCharacter($id) {
        
        $this->source->sadd("users:{$this->id}:characters", $id);
        //pobierz z powrotem wszystkie postacie
        $this->characters[] = $this->source->smembers("users:{$this->id}:characters");
        
    }
    
    public function getUserData($id) {

        if ($this->source) {
            $this->id = $id;

            $data = json_decode($this->source->get("users:$id"), true);

            if (isset($data['firstname'])) {
                $this->firstname = $data['firstname'];
            }
            if (isset($data['lastname'])) {
                $this->lastname = $data['lastname'];
            }
            if (isset($data['birthdate'])) {
                $this->birthdate = $data['birthdate'];
            }
            if (isset($data['register_date'])) {
                $this->register_date = $data['register_date'];
            }
            if (isset($data['status'])) {
                $this->status = $data['status'];
            }
            if (isset($data['email'])) {
                $this->email = $data['email'];
            }
            $this->characters = $this->source->smembers("users:{$this->id}:characters");

            $this->current_character_id = $this->source->get("users:$id:current_character");
            $this->password = $this->source->get("users:$id:password");

        }

    }

    public function login($email, $password) {
        
        //get user id
        $id = $this->source->get("emails:$email");
        if ($id) {
            if ($this->source->get("users:$id:password") == $password) {
                $authkey = md5($id.microtime());
                $this->source->set("users:$id:auth", $authkey);
                $this->source->set("auth:$authkey", $id);
                $this->logged_in = true;
                $this->authkey = $authkey;
                return $authkey;
            }
        }
        
        return null;
        
    }

    public function tryLogIn($authkey = null) {
        if ($authkey) {            
            $id = $this->source->get("auth:$authkey");           
            if ($id) {
                if ($this->source->get("users:{$id}:auth") == $authkey) {
                    $this->getUserData($id);
                    $this->logged_in = true;
                    $this->authkey = $authkey;
                    return true;
                }
            }
        }
        return false;
    }

    public function logout() {

        $this->source->del("auth:{$this->authkey}");
        $this->logged_in = false;
        $this->authkey = rand(0, 9999999999);
        $this->source->set("users:{$this->id}:auth", $this->authkey);
        $this->source->delete("users:{$this->id}:current_character");

        return 1;

    }

    public function refreshActive() {
        $this->source->set("active:{$this->id}", 1);
        $this->source->expire("active:{$this->id}", 900);
    }

    public function save() {
        
        //new id if user is saved for the first time
        if (!$this->id) {
            $this->id = $this->source->incr('global:IDUser');
        }
        $this->source->set("users:{$this->id}", json_encode($this->toArray()));
        $this->source->set("users:{$this->id}:password", $this->password);
        $this->source->set("users:{$this->id}:email", $this->email);
        //adds a key to get user id after providing his email
        $this->source->set("emails:{$this->email}", $this->id);
        $this->source->sadd('global:emails', $this->email);
        
    }

    public function activate($id) {
        $this->getUserData($id);
        $this->setStatus(self::STATUS_ACTIVE);
        $this->source->del("users:$id:activate_code");
        $this->save();
    }

    public function setActivationCode($id, $code) {
        $this->source->set("users:$id:activate_code", $code);
    }

    public function fetchActivationCode($id) {
        return $this->source->get("users:$id:activate_code");
    }

    public function isDuplicateEmail($email) {
        return $this->source->sismember('global:emails', $email);
    }


    public function getPasswordForUserId($id) {
        
        if ($this->source->exists("users:$id")) {
            return $this->source->get("users:$id:password");
        } else {
            return null;
        }
    }
    
    public function getPasswordForUserEmail($email) {
        
        if ($this->source->exists("emails:$email")) {
            $user_id = $this->source->get("emails:$email");
            return $this->source->get("users:$user_id:password");
        } else {
            return null;
        }
    }
    
}

?>
