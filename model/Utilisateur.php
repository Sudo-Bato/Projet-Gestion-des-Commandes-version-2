<?php

class Utilisateur {
    private $id;
    private $login;
    private $role;

    // constructeur
    public function __construct($id, $login, $role) {
        $this->id = $id;
        $this->login = $login;
        $this->role = $role;
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getRole() {
        return $this->role;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function setRole($role) {
        $this->role = $role;
    }
}