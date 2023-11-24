<?php

abstract class Salle
{
    protected $type;
    protected $description;

    public function __construct($type, $description)
    {
        $this->type = $type;
        $this->description = $description;
    }

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

class SalleVide extends Salle
{
    public function __construct($type, $description)
    {
        parent::__construct($type, $description);
    }

    public function afficherInformations()
    {
        echo "Type de salle: " . $this->getType() . "\n";
        echo "Description: " . $this->getDescription() . "\n";
    }
}

class SalleCombat extends Salle
{
    protected Personnage $monstre;

    public function __construct($type, $description, $monstre)
    {
        parent::__construct($type, $description);
        $this->monstre = $monstre;
    }

    // piege debuff 
    public function getMonstre()
    {
        return $this->monstre;
    }

    public function afficherInformations()
    {
        echo "Type de salle: " . $this->getType() . "\n";
        echo "Description: " . $this->getDescription() . "\n";
        echo "Monstre: " . $this->getMonstre()->getName() . "\n";
    }
}

class SalleBoss extends Salle
{
    protected $boss;

    public function __construct($type, $description, $boss)
    {
        parent::__construct($type, $description);
        $this->boss = $boss;
    }

    public function getBoss()
    {
        return $this->boss;
    }

    public function afficherInformations()
    {
        echo "Type de salle: " . $this->getType() . "\n";
        echo "Description: " . $this->getDescription() . "\n";
        echo "Boss: " . $this->getBoss() . "\n";
    }
}

class SalleEnigme extends Salle
{
    protected $enigme;

    protected $rep1;

    protected $rep2;

    protected $rep3;

    protected $bonne_rep;

    public function __construct($type, $description, $enigme, $rep1, $rep2, $rep3, $bonne_rep)
    {
        parent::__construct($type, $description);
        $this->enigme = $enigme;
        $this->rep1 = $rep1;
        $this->rep2 = $rep2;
        $this->rep3 = $rep3;
        $this->bonne_rep = $bonne_rep;
    }

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
                    $PVmax = $joueur->getPVMax();

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

class SalleMarchand extends Salle
{
    protected $arme1;
    protected $arme2;

    public function __construct($type, $description, $arme1, $arme2)
    {
        parent::__construct($type, $description);
        $this->arme1 = $arme1;
        $this->arme2 = $arme2;
    }

    public function getArme1()
    {
        return $this->arme1;
    }

    public function getArme2()
    {
        return $this->arme2;
    }

    public function setArme1($arme1)
    {
        $this->arme1 = $arme1;
    }

    public function setArme2($arme2)
    {
        $this->arme2 = $arme2;
    }

    public function afficherInformations() {
        echo "Type de salle: " . $this->getType() . "\n";
        echo "Description: " . $this->getDescription() . "\n";
        echo "Arme 1: " . $this->getArme1() . "\n";
        echo "Arme 2: " . $this->getArme2() . "\n";
    }
}
