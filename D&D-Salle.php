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
    protected $marchand;

    public function __construct($type, $description, $marchand)
    {
        parent::__construct($type, $description);
        $this->marchand = $marchand;
    }

    public function getMarchand()
    {
        return $this->marchand;
    }

    public function afficherInformations()
    {
        echo "Type de salle: " . $this->getType() . "\n";
        echo "Description: " . $this->getDescription() . "\n";
        echo "Marchand: " . $this->getMarchand() . "\n";
    }
}
