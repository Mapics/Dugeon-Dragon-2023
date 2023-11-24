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

    
    public function afficherInformations()
    {
        echo "Type de salle: " . $this->getType() . "\n";
        echo "Description: " . $this->getDescription() . "\n";
        echo "Arme 1: " . $this->getArme1() . "\n";
        echo "Arme 2: " . $this->getArme2() . "\n";
    }

}
