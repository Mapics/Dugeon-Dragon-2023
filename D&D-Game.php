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

    public function Jouer() {
        $ingame = true;
        while ($ingame) {
            // $this->isGameOver();
            $this->afficherMenu();
            $choix = readline();
            switch ($choix) {
                case 1:
                    $this->joueur->afficherStats();
                    break;
                case 2:
                    $this->joueur->afficherInventaire();
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
                $this->Combattre($this->currentSalle->getMonstre());
                if ($this->currentSalle->getMonstre()->isDead()) {
                    echo "est mort \n";
                    $this->joueur->gagnerExp($this->currentSalle->getMonstre()->getExp());
                    $this->joueur->gagnerOr($this->currentSalle->getMonstre()->getOr());
                    $this->joueur->gagnerObjet($this->currentSalle->getMonstre()->getObjet());
                    $this->joueur->isLevelUp();
                    $this->joueur->afficherStats();
                }
                break;
            case 'Marchand':
                $this->talkMarchand($this->currentSalle->getMarchand());
                break;
            case 'Enigme':
                $this->repondreEnigme($this->currentSalle->getEnigme());
                break;
            case 'Boss':
                $this->joueur->attaquer($this->currentSalle->getBoss());
                if ($this->currentSalle->getBoss()->isDead()) {
                    $this->joueur->gagnerExp($this->currentSalle->getMonstre()->getExp());
                    $this->joueur->gagnerOr($this->currentSalle->getMonstre()->getOr());
                    $this->joueur->gagnerObjet($this->currentSalle->getMonstre()->getObjet());
                    $this->joueur->LevelUp();
                    $this->joueur->afficherStats();
                }
                break;
        }
    }

    public function Combattre(Personnage $monstre)
    {
        while (!$this->currentSalle->getMonstre()->isDead() && !$this->joueur->isDead()) {
            $this->joueur->afficherStats();
            $monstre->afficherStats();
            $this->afficheAttaque();
            $choix = readline();
            switch ($choix) {
                case 1 :
                    $this->joueur->attaquer($monstre);
                    $monstre->attaquer($this->joueur);
                    break;
                case 2 :
                    $this->joueur->afficherInventaire();
                    break;
            }
        }
    }

    public function talkMarchand(Salle $marchand) {
        $choix = readline();
        switch ($choix) {
            case 1:
                $this->joueur->acheter($marchand->getArme1());
                break;
            case 2:
                $this->joueur->acheter($marchand->getArme2());
                break;
        }
    }

    public function afficheAttaque()
    {
        echo "1. Attaquer\n";
        echo "2. Utiliser un objet\n";
        echo "3. Fuir\n";
    }
}
