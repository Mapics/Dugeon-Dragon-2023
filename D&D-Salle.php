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

    abstract public function afficherDescription();
}

class SalleVide extends Salle
{
    public function afficherDescription()
    {
        return "Vous entrez dans une salle vide. Rien ne s'y trouve.";
    }
}

class SalleCombat extends Salle
{
    protected $monstre;

    public function __construct($type, $description, $monstre)
    {
        parent::__construct($type, $description);
        $this->monstre = $monstre;
    }

    public function getMonstre()
    {
        return $this->monstre;
    }

    public function afficherDescription()
    {
        return "Vous entrez dans une salle de combat. Un " . $this->monstre . " vous attaque !";
    }
}