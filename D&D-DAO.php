<?php

class ddDAO
{
    protected $bdd;

    public function __construct($bdd)
    {
        $this->bdd = $bdd;
    }

    public function ajouterArme($nom, $degat, $niv_requis)
    {
        $query = "INSERT INTO armes (Nom_arme, Degat, Niv_requis) VALUES (?, ?, ?)";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute([$nom, $degat, $niv_requis]);
    }

    public function getArmeById($id)
    {
        $query = "SELECT * FROM armes WHERE Id_arme = ?";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function modifierArme($id, $nom, $degat, $niv_requis)
    {
        $query = "UPDATE armes SET Nom_arme = ?, Degat = ?, Niv_requis = ? WHERE Id_arme = ?";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute([$nom, $degat, $niv_requis, $id]);
    }

    public function supprimerArme($id)
    {
        $query = "DELETE FROM armes WHERE Id_arme = ?";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute([$id]);
    }
}
