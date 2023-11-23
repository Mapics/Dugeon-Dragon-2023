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
}
class SalleVide extends Salle
{
    public function __construct($type, $description)
    {
        parent::__construct($type, $description);
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
}
