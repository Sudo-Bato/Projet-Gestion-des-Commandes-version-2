<?php

class Client {

    // Attributs
    private $id;
    private $nom;
    private $email;
    private $telephone;
    private $adresse_rue;
    private $adresse_cp;
    private $adresse_ville;


    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function getAdresseRue() {
        return $this->adresse_rue;
    }

    public function getAdresseCp() {
        return $this->adresse_cp;
    }

    public function getAdresseVille() {
        return $this->adresse_ville;
    }


    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    public function setAdresseRue($adresse_rue) {
        $this->adresse_rue = $adresse_rue;
    }

    public function setAdresseCp($adresse_cp) {
        $this->adresse_cp = $adresse_cp;
    }

    public function setAdresseVille($adresse_ville) {
        $this->adresse_ville = $adresse_ville;
    }

    
}

?>
