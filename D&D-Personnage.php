<?php
class Personnage
{
    protected $id;
    protected $name;
    protected $PV;
    protected $PVmax;
    protected $PA;
    protected $PD;
    protected $currentExp;
    protected $expForNextLevel;
    protected $level;

    function __construct($name)
    {
        $this->name = $name;
        $this->PVmax = 100;
        $this->PV = $this->PVmax;
        $this->PA = 10;
        $this->PD = 10;
        $this->currentExp = 0;
        $this->level = 1;
    }

    // getter
    public function getName()
    {
        return $this->name;
    }
    public function getPV()
    {
        return $this->PV;
    }
    public function getPA()
    {
        return $this->PA;
    }
    public function getPD()
    {
        return $this->PD;
    }
    public function getCurrentExp()
    {
        return $this->currentExp;
    }
    public function getLevel()
    {
        return $this->level;
    }

    // setter
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setPV($PV)
    {
        $this->PV = $PV;
    }
    public function setPA($PA)
    {
        $this->PA = $PA;
    }
    public function setPD($PD)
    {
        $this->PD = $PD;
    }
    public function setCurrentExp($currentExp)
    {
        $this->currentExp = $currentExp;
    }
    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function Equip()
    {
        //TODO
    }

    public function setSave()
    {
        //TODO
    }

    public function attaquer(Personnage $cible)
    {
        $degats = $this->getPA() - $cible->getPD();
        if ($degats > 0) {
            $cible->PV -= $degats;
        }
    }

    public function GainExp(Int $exp)
    {
        $this->currentExp += $exp;
        if ($this->currentExp >= $this->expForNextLevel) {
            $this->level += 1;
            $this->currentExp = 0;
            $this->expForNextLevel = $this->expForNextLevel * 1.5;
        }
    }

    public function isDead()
    {
        if ($this->PV <= 0) {
            return true;
        } else {
            return false;
        }
    }

    public function recevoirDegats($degats)
    {
        $this->PV -= $degats;
    }
    public function LevelUp()
    {
        $this->level += 1;
        $this->PV = $this->PV * 1.5;
        $this->PA = $this->PA * 1.5;
        $this->PD = $this->PD * 1.5;
        $this->expForNextLevel = $this->expForNextLevel * 1.5;
    }

    public function afficherStats()
    {
        echo "Statistiques: \n";
        echo "Nom: " . $this->getName() . "\n";
        echo "Vie: " . $this->getPV() . "\n";
        echo "Niveau: " . $this->getLevel() . "\n";
        echo "Dégâts: " . $this->getPA() . "\n";
        echo "Défense: " . $this->getPD() . "\n";
    }

    public function afficherInventaire()
    {
        echo "Inventaire: \n";
        // echo "Arme: " . $this->getArme() . "\n";
    }
}

class Joueur extends Personnage
{
    protected $inventaire;

    function __construct($name, $inventaire)
    {
        parent::__construct($name);
        $this->inventaire = $inventaire;
    }

    // getter
    public function getInventaire()
    {
        return $this->inventaire;
    }

    // setter
    public function setInventaire($inventaire)
    {
        $this->inventaire = $inventaire;
    }
}

class Monstre extends Personnage
{
    protected $level;
    protected $dropExp;
    protected $dropGold;

    function __construct($name, $PV, $PA, $PD, $level, $dropExp, $dropGold)
    {
        parent::__construct($name);
        $this->PV = $PV * $level;
        $this->PA = $PA * $level;
        $this->PD = $PD * $level;
        $this->level = $level;
        $this->dropExp = $dropExp;
        $this->dropGold = $dropGold;
    }

    // getter
    public function getLevel() {
        return $this->level;
    }

    public function getNom() {
        return $this->name;
    }

    // setter
    public function setLevel($level)
    {
        $this->level = $level;
    }
}

class Objet
{
    protected $nomObjet;

    protected $typeObjet;

    protected $bonus;

    protected $malus;

    function __construct($nomObjet, $typeObjet, $bonus, $malus)
    {
        $this->nomObjet = $nomObjet;
        $this->typeObjet = $typeObjet;
        $this->bonus = $bonus;
        $this->malus = $malus;
    }

    // getter
    public function getNomObjet()
    {
        return $this->nomObjet;
    }

    public function getTypeObjet()
    {
        return $this->typeObjet;
    }

    public function getBonus()
    {
        return $this->bonus;
    }

    public function getMalus()
    {
        return $this->malus;
    }

    // setter

    public function setNomObjet($nomObjet)
    {
        $this->nomObjet = $nomObjet;
    }

    public function setTypeObjet($typeObjet)
    {
        $this->typeObjet = $typeObjet;
    }

    public function setBonus($bonus)
    {
        $this->bonus = $bonus;
    }

    public function setMalus($malus)
    {
        $this->malus = $malus;
    }

    public function afficherObjet()
    {
        echo "Nom de l'objet : " . $this->nomObjet . ", Type d'objet : " . $this->typeObjet . ", Bonus : " . $this->bonus . ", Malus : " . $this->malus . "\n";
    }

    public function utiliserObjet(Personnage $cible)
    {
        if ($this->typeObjet == "arme") {
            $cible->setPA($cible->getPA() + $this->bonus);
            $cible->setPD($cible->getPD() + $this->malus);
        } elseif ($this->typeObjet == "potion") {
            $cible->setPV($cible->getPV() + $this->bonus);
        }
    }
}

class Arme extends Objet
{

    protected $typeArme;
    protected $effet;
    protected $degats;
    function __construct($nomObjet, $typeObjet, $bonus, $malus, $typeArme, $degats, $effet)
    {
        parent::__construct($nomObjet, $typeObjet, $bonus, $malus);
        $this->typeArme = $typeArme;
        $this->effet = $effet;
        $this->degats = $degats;
    }

    //getter
    public function getDegats() {
        return $this->degats;
    }

    public function getTypeArme()
    {
        return $this->typeArme;
    }

    public function getEffet()
    {
        return $this->effet;
    }

    //setter
    public function setDegats($degats) {
        $this->degats = $degats;
    }

    public function setTypeArme($typeArme)
    {
        $this->typeArme = $typeArme;
    }

    public function setEffet($effet)
    {
        $this->effet = $effet;
    }

    public function afficherArme()
    {
        echo "Nom de l'arme : " . $this->nomObjet . ", Type d'arme : " . $this->typeArme . ", Bonus : " . $this->bonus . ", Malus : " . $this->malus . ", Dégats : " . $this->degats . ", Effet : " . $this->effet . "\n";
    }

    public function utiliserObjet(Personnage $cible)
    {
        if ($this->typeObjet == "arme") {
            $cible->setPA($cible->getPA() + $this->bonus);
            $cible->setPD($cible->getPD() + $this->malus);
        }
    }
}

class Inventaire
{
    protected $arme;

    function __construct($arme)
    {
        $this->arme = $arme;
    }

    // getter
    public function getArme()
    {
        return $this->arme;
    }

    // setter
    public function setArme($arme)
    {
        $this->arme = $arme;
    }

    public function afficherInventaire()
    {
        echo "Voici les objets dans votre inventaire :";
        foreach ($this->arme as $key => $value) {
            echo $key . " : " . $value . "\n";
        }
    }
}
