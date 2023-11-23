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
