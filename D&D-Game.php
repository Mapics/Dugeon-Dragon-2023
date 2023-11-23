<?php
class DD_Game
{
    protected $ddDAO;
    protected $personnage;

    function __construct($ddDAO)
    {
        $this->ddDAO = $ddDAO;
    }

    // getter
    public function getDdDAO()
    {
        return $this->ddDAO;
    }
    public function getPersonnage()
    {
        return $this->personnage;
    }

    // setter 
    public function setDdDAO($ddDAO)
    {
        $this->ddDAO = $ddDAO;
    }
    public function setPersonnage($personnage)
    {
        $this->personnage = $personnage;
    }

    public function setPlayerSave(Personnage $joueur)
    {
        $this->personnage = $joueur;
        $this->Jouer();
    }

    public function initGame($dd_dao)
    {
        echo "1. Nouvelle partie \n";
        echo "2. Charger partie \n";
        $choix = readline();
        switch ($choix) {
            case 1:
                echo "Chissisez votre pseudo :\n";
                $name = readline();
                $this->player = new Player($name, array());
                $this->Jouer();
                break;
            case 2:
                echo "Entrez votre precedent pseudo :\n";
                $name = readline();
                $this->setPlayerSave($this->ddDAO->GetSaveOf($name));
                break;
        }
    }

    public function afficherMenu()
    {
        echo "1. Afficher les informations du personnage\n";
        echo "2. Afficher les informations de l'inventaire\n";
        echo "3. Se déplacer\n";
        echo "4. Sauvegarder\n";
        echo "6. Quitter\n";
    }

    public function afficheAttaque()
    {
        echo "1. Attaquer\n";
        echo "2. Voirs stats\n";
    }

    public function afficheSalleVide()
    {
        echo "Vous entrez dans une salle vide. Rien ne s'y trouve.\n";
    }

    public function afficheSalleCombat(Personnage $monstre)
    {
        echo "Vous entrez dans une salle de combat. Un " . $monstre->getName() . " vous attaque !\n";
    }

    public function afficheSalleMarhcand(Salle $marchand)
    {
        echo "Vous entrez dans une salle de marchand. Un Marchand vous propose des objets !\n";
    }

    public function afficheSallePiege(Salle $piege)
    {
        echo "Vous entrez dans une salle de piege. Un piege vous attaque !\n";
    }

}


function startNewGame()
{

}

function ContinueGame()
{

}
?>