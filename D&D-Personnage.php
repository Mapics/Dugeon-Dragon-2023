<?php
class Personnage
{
    protected $id;
    protected $name;
    protected $PV;
    protected $PA;
    protected $PD;
    protected $currentExp;
    protected $level;
    protected $armor;

    function __construct($name) {
        $this->name = $name;
        $this->PV = 100;
        $this->PA = 10;
        $this->PD = 10;
        $this->currentExp = 0;
        $this->level = 1;
        $this->armor = 0;
    }

    // getter
    public function getName() {
        return $this->name;
    }
    public function getPV() {
        return $this->PV;
    }
    public function getPA() {
        return $this->PA;
    }
    public function getPD() {
        return $this->PD;
    }
    public function getCurrentExp() {
        return $this->currentExp;
    }
    public function getLevel() {
        return $this->level;
    }
    public function getArmor() {
        return $this->armor;
    }

    // setter
    public function setName($name) {
        $this->name = $name;
    }
    public function setPV($PV) {
        $this->PV = $PV;
    }
    public function setPA($PA) {
        $this->PA = $PA;
    }
    public function setPD($PD) {
        $this->PD = $PD;
    }
    public function setCurrentExp($currentExp) {
        $this->currentExp = $currentExp;
    }
    public function setLevel($level) {
        $this->level = $level;
    }
    public function setArmor($armor) {
        $this->armor = $armor;
    }
    

    public function Equip() {
        //TODO
    }

    public function attaquer(Personnage $cible) {
        $degats = $this->getPA() - $cible->getPD();
        if ($degats > 0) {
            $cible->PV -= $degats;
        }
    }

    public function GagnerExp() {
        $this->Xp;
    }
}
?>