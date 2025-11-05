<?php

class Commande {

    // Attributs
    private $id;
    private $client_id;
    private $date_commande;
    private $statut;
    

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getClientId() {
        return $this->client_id;
    }

    public function getDateCommande() {
        return $this->date_commande;
    }

    public function getStatut() {
        return $this->statut;
    }
    

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setClientId($client_id) {
        $this->client_id = $client_id;
    }

    public function setDateCommande($date_commande) {
        $this->date_commande = $date_commande;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }
}

?>
