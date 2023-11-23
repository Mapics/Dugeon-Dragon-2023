<?php
class Personnage
{
    protected $id;
    protected $name;
    protected $PV;
    protected $PA;
    protected $PD;
    protected $currentExp;
    protected $expForNextLevel;
    protected $level;

    function __construct($name) {
        $this->name = $name;
        $this->PV = 100;
        $this->PA = 10;
        $this->PD = 10;
        $this->currentExp = 0;
        $this->level = 1;
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

    public function Equip() {
        //TODO
    }

    public function attaquer(Personnage $cible) {
        $degats = $this->getPA() - $cible->getPD();
        if ($degats > 0) {
            $cible->PV -= $degats;
        }
    }

    public function GainExp(Int $exp) {
        $this->currentExp += $exp;
        if ($this->currentExp >= $this->expForNextLevel) {
            $this->level += 1;
            $this->currentExp = 0;
            $this->expForNextLevel = $this->expForNextLevel * 1.5;
        }
    }

    public function isDead() {
        if ($this->PV <= 0) {
            return true;
        } else {
            return false;
        }
    }

    
    public function recevoirDegats($degats) {
        $this->PV -= $degats;
    }
    public function LevelUp() {
        $this->level += 1;
        $this->PV = $this->PV * 1.5;
        $this->PA = $this->PA * 1.5;
        $this->PD = $this->PD * 1.5;
        $this->expForNextLevel = $this->expForNextLevel * 1.5;
    }

    public function afficherStats() {
        echo "Nom : " . $this->name . ", Niveau de puissance : " . $this->level . ", Points de vie : " . $this->PV . ", Points d'attaque : " . $this->PA . ", Points de défense : " . $this->PD . "\n";
    }
}

class Player extends Personnage {
    protected $inventaire;

    function __construct($name, $inventaire) {
        parent::__construct($name);
        $this->inventaire = $inventaire;
    }
}

class Monstre extends Personnage {
    protected $level;

    function __construct($name, $level) {
        parent::__construct($name);
        $this->PV = 100 * $level;
        $this->PA = 10 * $level;
        $this->PD = 10 * $level;
        $this->level = $level;
    }

    // getter
    public function getLevel() {
        return $this->level;
    }

    // setter
    public function setLevel($level) {
        $this->level = $level;
    }

    public function attaquer(Personnage $cible) {
        $degats = $this->getPA() - $cible->getPD();
        if ($degats > 0) {
            $cible->PV -= $degats;
        }
    }
}
?>