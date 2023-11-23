<?php
class Game {
    protected $ddDAO;
    protected $personnage;

    function __construct($ddDAO, $personnage) {
        $this->ddDAO = $ddDAO;
        $this->personnage = $personnage;
    }

    // getter
    public function getDdDAO() {
        return $this->ddDAO;
    }
    public function getPersonnage() {
        return $this->personnage;
    }

    // setter 
    public function setDdDAO($ddDAO) {
        $this->ddDAO = $ddDAO;
    }
    public function setPersonnage($personnage) {
        $this->personnage = $personnage;
    }

    public function initGame() {
        echo "1. Nouvelle partie \n";
        echo "2. Charger partie \n";
    }

    public function afficherMenu() {
        echo "1. Afficher les informations du personnage\n";
        echo "2. Afficher les informations de l'inventaire\n";
        echo "3. Se déplacer\n";
        echo "4. Sauvegarder\n";
        echo "6. Quitter\n";
    }

    public function afficheAttaque() {
        echo "1. Attaquer\n";
        echo "2. Voirs stats\n";
    }

    public function afficheSalleVide() {
        echo "Vous entrez dans une salle vide. Rien ne s'y trouve.\n";
    }

    public function afficheSalleCombat(Personnage $monstre) {
        echo "Vous entrez dans une salle de combat. Un " . $monstre->getName() . " vous attaque !\n";
    }

    public function afficheSalleMarhcand(Salle $marchand) {
        echo "Vous entrez dans une salle de marchand. Un Marchand vous propose des objets !\n";
    }

    public function afficheSallePiege(Salle $piege) {
        echo "Vous entrez dans une salle de piege. Un piege vous attaque !\n";
    }
}

?>
