<?php

class Produit {

    // Attributs
    private $id;
    private $nom;
    private $description;
    private $prix;
    private $stock;

    
    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrix() {
        return $this->prix;
    }

    public function getStock() {
        return $this->stock;
    }


    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setPrix($prix) {
        $this->prix = $prix;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }
}

?>
