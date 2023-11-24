<?php
class DD_Game
{
    protected $ddDAO;
    protected $joueur;
    protected $currentSalle;

    function __construct($ddDAO)
    {
        $this->ddDAO = $ddDAO;
    }

    // getter
    public function getDdDAO()
    {
        return $this->ddDAO;
    }
    public function getJoueur()
    {
        return $this->joueur;
    }
    public function getCurrentSalle()
    {
        return $this->currentSalle;
    }

    // setter 
    public function setDdDAO($ddDAO)
    {
        $this->ddDAO = $ddDAO;
    }
    public function setJoueur($joueur)
    {
        $this->joueur = $joueur;
    }

    public function setPlayerSave(Personnage $joueur)
    {
        $this->joueur = $joueur;
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
                $this->joueur = new Joueur($name, array());
                $this->Jouer();
                break;
            case 2:
                echo "Entrez votre precedent pseudo :\n";
                $name = readline();
                $this->setPlayerSave($this->ddDAO->GetSaveOf($name));
                $this->Jouer();
                break;
        }
    }

    public function Jouer()
    {
        $ingame = true;
        while ($ingame) {
            $this->afficherMenu();
            $choix = readline();
            switch ($choix) {
                case 1:
                    $this->joueur->afficherStats();
                    break;
                case 2:
                    $this->joueur->getInventaire()->afficherInventaire();
                    break;
                case 3:
                    $this->seDeplacer();
                    break;
                case 4:
                    $this->ddDAO->Save($this->joueur);
                    break;
                case 5:
                    echo "A bientot !\n";
                    $ingame = false;
                    break;
            }
        }
    }

    public function seDeplacer()
    {
        $this->currentSalle = $this->ddDAO->salleAleatoire();
        $this->SalleInteraction();
    }

    public function afficherMenu()
    {
        echo "1. Afficher les informations du personnage\n";
        echo "2. Afficher les informations de l'inventaire\n";
        echo "3. Se déplacer\n";
        echo "4. Sauvegarder\n";
        echo "5. Quitter\n";
    }

    public function SalleInteraction()
    {
        $this->currentSalle->afficherInformations();
        $type = $this->currentSalle->getType();
        switch ($type) {
            case 'Vide':
                echo "La salle dans laquelle vous venez d'entrer est totalement vide...";
                break;
            case 'Combat':
                $this->Combattre($this->joueur, $this->currentSalle->getMonstre());
                if ($this->currentSalle->getMonstre()->isDead()) {
                    echo "est mort \n";
                    $monstre = $this->currentSalle->getMonstre();

                    $expGagnee = $monstre->getExp();
                    $orGagne = $monstre->getOr();
                    $objetGagne = $monstre->getObjet();

                    $this->joueur->gagnerExp($expGagnee);
                    $this->joueur->gagnerOr($orGagne);
                    $this->joueur->gagnerObjet($objetGagne);
                    $this->joueur->LevelUp();
                    }

                    $this->joueur->afficherStats();
                }
                break;
            case 'Marchand':
                $this->joueur->acheter($salle->getMarchand());
                break;
            case 'Enigme':
                $this->joueur->repondreEnigme($salle->getEnigme());
                break;
            case 'Boss':
                $this->joueur->attaquer($salle->getBoss());
                if ($salle->getBoss()->isDead()) {
                    // TODO
                    // $this->joueur->gagnerExp($salle->getBoss()->getExp());
                    // $this->joueur->gagnerOr($salle->getBoss()->getOr());
                    // $this->joueur->gagnerObjet($salle->getBoss()->getObjet());
                    // $this->joueur->LevelUp();
                    $this->joueur->afficherStats();
                }
                break;
        }
    }

    public function Combattre(Personnage $joueur, Personnage $monstre)
    {
        while (!$this->currentSalle->getMonstre()->isDead()) {

            $joueur->afficherStats();
            $monstre->afficherStats();
            // TODO VVV
            // $joueur->afficheAttaque();
            $choix = readline();
            switch ($choix) {
                case 1:
                    $joueur->attaquer($monstre);
                    $monstre->attaquer($joueur);
                    break;
                case 2:
                    $joueur->afficherStats();
                    break;
            }
        }
    }
    public function afficheAttaque()
    {
        echo "1. Attaquer\n";
        echo "2. Voirs stats\n";
    }
}
