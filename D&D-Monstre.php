<?php
class Monstre {
    protected $id;
    protected $name;
    protected $pv;
    protected $pa;
    protected $pd;
    protected $level;
    function __construct($name, $level) {
        $this->name = $name;
        $this->pv = 100 * $level;
        $this->pa = 10 * $level;
        $this->pd = 10 * $level;
        $this->level = $level;
    }

    // getters
    public function getName() {
        return $this->name;
    }

    public function getPV() {
        return $this->pv;
    }

    public function getPA() {
        return $this->pa;
    }

    public function getPD() {
        return $this->pd;
    }

    public function getLevel() {
        return $this->level;
    }

    // setters
    public function setName($name) {
        $this->name = $name;
    }

    public function setPV($pv) {
        $this->pv = $pv;
    }

    public function setPA($pa) {
        $this->pa = $pa;
    }

    public function setPD($pd) {
        $this->pd = $pd;
    }

    public function setLevel($level) {
        $this->level = $level;
    }

    //Methods
    public function attaquer(Monstre $cible) {
        $degatsE = $this->getPA() - $cible->getPD();
        if ($degatsE > 0) {
            $cible->PV -= $degatsE;
        }
    }

    public function recevoirDegats($degats) {
        $this->pv -= $degats;
    }

    public function isDead() {
        return $this->pv <= 0;
    }

    public function afficherStats() {
        echo "Nom : " . $this->name . ", Niveau de puissance : " . $this->level . ", Points de vie : " . $this->pv . ", Points d'attaque : " . $this->pa . ", Points de défense : " . $this->pd . "\n";
    }
}
?>