<?php

//CREATION DES SALLES EN ABSTRAIT
abstract class Salle
{
    protected $type;        //TYPE DE SALLE
    protected $description; //DESCRIPTION DE LA SALLE

    public function __construct($type, $description)        //CONSTRUCTEUR
    {
        $this->type = $type;
        $this->description = $description;
    }

    //GETTERS
    public function getType()       
    {
        return $this->type;
    }

    public function getDescription()        
    {
        return $this->description;
    }

    abstract public function afficherInformations();
}

class SalleVide extends Salle       //SALLE VIDE
{
    public function __construct($type, $description)        //CONSTRUCTEUR
    {
        parent::__construct($type, $description);
    }

    public function afficherInformations()      //AFFICHE LES INFORMATIONS DE LA SALLE
    {
        echo "Type de salle: " . $this->getType() . "\n";
        echo "Description: " . $this->getDescription() . "\n";
    }
}

class SalleCombat extends Salle             //SALLE DE COMBAT
{
    protected Personnage $monstre;      //MONSTRE DE LA SALLE

    public function __construct($type, $description, $monstre)      //CONSTRUCTEUR
    {
        parent::__construct($type, $description);
        $this->monstre = $monstre;
    }

    //GETTERS
    public function getMonstre()
    {
        return $this->monstre;
    }

    //AFFICHE LES INFORMATIONS DE LA SALLE
    public function afficherInformations()
    {
        echo "Type de salle: " . $this->getType() . "\n";
        echo "Description: " . $this->getDescription() . "\n";
        echo "Monstre: " . $this->getMonstre()->getName() . "\n";
    }
}

class SalleBoss extends Salle   //SALLE DE BOSS
{
    protected $boss;        //BOSS DE LA SALLE

    public function __construct($type, $description, $boss)     //CONSTRUCTEUR
    {
        parent::__construct($type, $description);
        $this->boss = $boss;
    }

    //GETTERS
    public function getBoss()
    {
        return $this->boss;
    }

    public function afficherInformations()  //AFFICHE LES INFORMATIONS DE LA SALLE
    {
        echo "Type de salle: " . $this->getType() . "\n";
        echo "Description: " . $this->getDescription() . "\n";
        echo "Boss: " . $this->getBoss() . "\n";
    }
}

class SalleEnigme extends Salle     //SALLE D'ENIGME
{
    protected $enigme;      //ENIGME DE LA SALLE

    protected $rep1;        //REPONSE 1 DE L'ENIGME

    protected $rep2;        //REPONSE 2 DE L'ENIGME

    protected $rep3;        //REPONSE 3 DE L'ENIGME

    protected $bonne_rep;   //BONNE REPONSE DE L'ENIGME

        public function __construct($type, $description, $enigme, $rep1, $rep2, $rep3, $bonne_rep)   //CONSTRUCTEUR
        {
        parent::__construct($type, $description);
        $this->enigme = $enigme;
        $this->rep1 = $rep1;
        $this->rep2 = $rep2;
        $this->rep3 = $rep3;
        $this->bonne_rep = $bonne_rep;
    }

    //GETTERS
    public function getEnigme()
    {
        return $this->enigme;
    }

    public function getRep1()
    {
        return $this->rep1;
    }

    public function getRep2()
    {
        return $this->rep2;
    }

    public function getRep3()
    {
        return $this->rep3;
    }

    public function getBonneRep()
    {
        return $this->bonne_rep;
    }

    //SETTERS
    public function setEnigme($enigme)
    {
        $this->enigme = $enigme;
    }

    public function setRep1($rep1)
    {
        $this->rep1 = $rep1;
    }

    public function setRep2($rep2)
    {
        $this->rep2 = $rep2;
    }

    public function setRep3($rep3)
    {
        $this->rep3 = $rep3;
    }

    public function setBonneRep($bonne_rep)
    {
        $this->bonne_rep = $bonne_rep;
    }

    //AFFICHE LES INFORMATIONS DE LA SALLE
    public function afficherInformations()
    {
        echo "Type de salle: " . $this->getType() . "\n";
        echo "Description: " . $this->getDescription() . "\n";
        echo "Enigme: " . $this->getEnigme() . "\n";
        $reponses = [$this->getRep1(), $this->getRep2(), $this->getRep3(), $this->getBonneRep()];

        shuffle($reponses);

        foreach ($reponses as $key => $reponse) {
            echo $key + 1 . " - " . $reponse . "\n";
        }
    }

    //REND LES REPONSES DE L'ENIGME ALEATOIRES, ET FAIT PERDRE DES PV, DE L'ARGENT OU UNE ARME AU JOUEUR SI IL SE TROMPE
    public function repondreEnigme($enigme, $joueur)
    {
        echo "Énigme: " . $enigme->getEnigme() . "\n";
        echo "Choisissez la réponse (1, 2 ou 3): \n";

        $reponses = [
            $enigme->getRep1(),
            $enigme->getRep2(),
            $enigme->getRep3(),
        ];

        shuffle($reponses);

        foreach ($reponses as $key => $reponse) {
            echo $key + 1 . " - " . $reponse . "\n";
        }

        $bonne_rep = $enigme->GetBonneRep();
        $choix = readline("Votre réponse : ");

        if ($choix == $bonne_rep) {
            echo "Bonne réponse ! Vous avez résolu l'énigme.\n";
            echo "Vous pouvez continuer votre aventure.\n";
        } else {
            echo "Mauvaise réponse. L'énigme reste non résolue.\n";

            $rand = rand(1, 3);

            switch ($rand) {
                case 1:
                    $PV = $joueur->getPV();
                    $PVmax = $joueur->getPVmax();

                    $PVperdus = $PVmax * 0.1;

                    if ($PV > $PVperdus) {
                        $nouveauPV = $PV - $PVperdus;
                        $joueur->setPV($nouveauPV);
                        echo "Vous avez perdu 10% de vos points de vie.\nPoints de vie restants : " . $nouveauPV . ".\n";
                    } else {
                        // GAMEOVER
                    }
                    break;
                case 2:
                    $argentActuel = $joueur->getArgent();
                    $nouveauArgent = $argentActuel / 2;
                    $joueur->setArgent($nouveauArgent);
                    echo "Vous avez perdu la moitié de votre argent.\n";
                    break;
                case 3:
                    $armesJoueur = $joueur->getArmes();
                    if (!empty($armesJoueur)) {
                        $armePerdue = array_rand($armesJoueur, 1);
                        $armePerdueNom = $armesJoueur[$armePerdue]->getNom();
                        unset($armesJoueur[$armePerdue]);
                        $joueur->setArmes($armesJoueur);
                        echo "Vous avez perdu l'arme : $armePerdueNom.\n";
                    } else {
                        echo "Vous n'avez aucune arme à perdre.\n";
                    }
                    break;
                default:
                    echo "Erreur.\n";
                    break;
            }
        }
    }
}

class SalleMarchand extends Salle       //SALLE DE MARCHAND
{
    protected $arme1;       //ARME 1 DU MARCHAND
    protected $arme2;       //ARME 2 DU MARCHAND

    public function __construct($type, $description, $arme1, $arme2)        //CONSTRUCTEUR
    {
        parent::__construct($type, $description);
        $this->arme1 = $arme1;
        $this->arme2 = $arme2;
    }

    //GETTERS
    public function getArme1()
    {
        return $this->arme1;
    }

    public function getArme2()
    {
        return $this->arme2;
    }

    //SETTERS

    public function setArme1($arme1)
    {
        $this->arme1 = $arme1;
    }

    public function setArme2($arme2)
    {
        $this->arme2 = $arme2;
    }


    //AFFICHE LES INFORMATIONS DE LA SALLE
    public function afficherInformations()
    {
        echo "Type de salle: " . $this->getType() . "\n";
        echo "Description: " . $this->getDescription() . "\n";
        echo "Arme 1: " . $this->getArme1() . "\n";
        echo "Arme 2: " . $this->getArme2() . "\n";
    }
}
