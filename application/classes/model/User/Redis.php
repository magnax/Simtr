<?php

class Model_User_Redis extends Model_User {

    public function setCurrentCharacter($id) {

        $this->current_character_id = $id;
        $this->source->set("users:{$this->id}:current_character", $id);

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
            if (isset($data['email'])) {
                $this->email = $data['email'];
            }
            if (isset($data['characters'])) {
                $this->characters = $data['characters'];
            }

            $this->current_character_id = $this->source->get("users:$id:current_character");

        }

    }

    public function login($id, $password) {
        if ($this->source->get("users:$id:password") == $password) {
            $authkey = md5($id.microtime());
            $this->source->set("users:$id:auth", $authkey);
            $this->source->set("auth:$authkey", $id);
            return $authkey;
        } else {
            return false;
        }
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
        $this->source->set("users:{$this->id}", json_encode($this->toArray()));
    }

    public function createNew($post) {
        $this->id = $this->source->incr('global:IDUser');

        $this->email = $post['email'];
        $this->register_date = date("Y-m-d H:i:s");
        $this->characters = array();

        $this->source->sadd('global:emails', $post['email']);
        $this->source->set("users:{$this->id}:password", $post['pass']);
        $this->source->set("users:{$this->id}:email", $post['email']);

        return $this;
    }
}

?>
