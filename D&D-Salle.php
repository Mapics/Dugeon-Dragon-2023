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

    public function __construct($type, $description, $enigme)
    {
        parent::__construct($type, $description);
        $this->enigme = $enigme;
    }

    public function getEnigme()
    {
        return $this->enigme;
    }

    public function afficherInformations()
    {
        echo "Type de salle: " . $this->getType() . "\n";
        echo "Description: " . $this->getDescription() . "\n";
        echo "Enigme: " . $this->getEnigme() . "\n";
    }
}

class SalleMarchand extends Salle
{
    protected $arme1;

    protected $arme2;

    public function __construct($type, $description, $arme1, $arme2) {
        parent::__construct($type, $description);
        $this->arme1 = $arme1;
        $this->arme2 = $arme2;
    }

    public function getArme1() {
        return $this->arme1;
    }

    public function getArme2() {
        return $this->arme2;
    }

    public function setArme1($arme1) {
        $this->arme1 = $arme1;
    }

    public function setArme2($arme2) {
        $this->arme2 = $arme2;
    }
    
    public function afficherInformations() {
        echo "Type de salle: " . $this->getType() . "\n";
        echo "Description: " . $this->getDescription() . "\n";
        echo "Arme 1: " . $this->getArme1() . "\n";
        echo "Arme 2: " . $this->getArme2() . "\n";
    }
}
