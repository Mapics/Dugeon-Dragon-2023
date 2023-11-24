<?php
class DD_Game
{
    // GESTION DE DONNEES JOUEUR ACTUELLEMENT EN JEU ET SALLE ACTUELLEMENT EN JEU
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
        // DEFINIR LE JOUEUR ET DEMARRER LE JEU EN TANT QUE JOUEUR SELECTIONNE
        $this->joueur = $joueur;
        $this->Jouer();
    }

    // LANCEMENT DU JEU ET AFFICHAGE CLI
    public function initGame($dd_dao)
    {
        echo "1. Nouvelle partie \n";
        echo "2. Charger partie \n";
        $choix = readline();
        switch ($choix) {
            case 1:
                echo "Choissisez votre pseudo :\n";
                $name = readline();
                $this->joueur = new Joueur($name, new Inventaire(""));
                $this->ddDAO->ajouterJoueurBDD($this->joueur);
                // $this->ddDAO->ajouterInventaireBDD($th, $this->joueur);
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

    // GESTION DU JEU ET AFFICHAGE CLI
    public function Jouer()
    {
        $ingame = true;
        while ($ingame) {
            // $this->isGameOver();
            $this->afficherMenu();
            $choix = readline();
            switch ($choix) {
                case 1:
                    // AFFICHER LES INFORMATIONS DU PERSONNAGE
                    $this->joueur->afficherStats();
                    break;
                case 2:
                    // AFFICHER L'INVENTAIRE DU PERSONNAGE
                    $this->joueur->afficherInventaire();
                    break;
                case 3:
                    // AVANCER D'UNE SALLE
                    $this->seDeplacer();
                    break;
                case 4:
                    // SAUVEGARDER
                    $this->ddDAO->Sauvegarder($this->joueur);
                    break;
                case 5:
                    // QUITTER
                    echo "A bientot !\n";
                    $ingame = false;
                    break;
            }
        }
    }

    public function seDeplacer()
    {
        // AVANCER D'UNE SALLE GENEREE DE MANIERE ALEATOIRE ET JOUER CE QU'IL DOIT SE PASSER DE LA SALLE EN FONCTION DE SON TYPE
        $this->currentSalle = $this->ddDAO->salleAleatoire();
        $this->SalleInteraction();
    }

    public function afficherMenu()
    {
        // MENU ENTRE DEUX SALLES
        echo "1. Afficher les informations du personnage\n";
        echo "2. Afficher les informations de l'inventaire\n";
        echo "3. Se déplacer\n";
        echo "4. Sauvegarder\n";
        echo "5. Quitter\n";
    }

    public function SalleInteraction()
    {
        // JOUE CHAQUE SALLE EN FONCTION DE SON TYPE
        $this->currentSalle->afficherInformations();
        $type = $this->currentSalle->getType();
        switch ($type) {
            case 'Vide':
                // SI SALLE VIDE
                echo "La salle dans laquelle vous venez d'entrer est totalement vide...";
                break;
            case 'Combat':
                // SI SALLE COMBAT
                // RECUPERE LE MONSTRE GENERE DE MANIERE ALEATOIRE DANS LA SALLECOMBAT
                $this->Combattre($this->currentSalle->getMonstre());
                // SI VICTOIRE JOUEUR
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
                // SI SALLE MARCHAND
                $this->talkMarchand($this->currentSalle);
                break;
            case 'Enigme':
                // SI SALLE ENIGME
                $this->currentSalle->repondreEnigme($this->currentSalle->getEnigme());
                break;
            case 'Boss':
                // SI SALLE BOSS
                $this->joueur->attaquer($this->currentSalle->getBoss());
                if ($this->currentSalle->getBoss()->isDead()) {
                    $this->joueur->gagnerExp($this->currentSalle->getMonstre()->getExp());
                    $this->joueur->gagnerOr($this->currentSalle->getMonstre()->getOr());
                    $this->joueur->gagnerObjet($this->currentSalle->getMonstre()->getObjet());
                    $this->joueur->isLevelUp();
                    $this->joueur->afficherStats();
                }
                break;
        }
    }

    // FONCTION DE COMBAT ENTRE JOUEUR ET MONSTRE
    public function Combattre(Personnage $monstre)
    {
        // SI LE MONSTRE N'EST PAS MORT
        while (!$this->currentSalle->getMonstre()->isDead() && !$this->joueur->isDead()) {
            $this->joueur->afficherStats();
            $monstre->afficherStats();
            $this->afficheAttaque();
            $choix = readline();
            switch ($choix) {
                case 1:
                    $this->joueur->attaquer($monstre);
                    $monstre->attaquer($this->joueur);
                    break;
                case 2:
                    $this->joueur->afficherInventaire();
                    break;
            }
        }
    }

    // FONCTION MARCHAND
    public function talkMarchand(SalleMarchand $marchand)
    {
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

    // MENU EN COMBAT
    public function afficheAttaque()
    {
        echo "1. Attaquer\n";
        echo "2. Utiliser un objet\n";
        echo "3. Fuir\n";
    }
}
