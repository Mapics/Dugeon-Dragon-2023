<?php


class Personnage        //CLASSE PERSONNAGE
{
    protected $id;      //ID DU PERSONNAGE
    protected $name;        //NOM DU PERSONNAGE
    protected $PV;          //PV DU PERSONNAGE
    protected $PVmax;       //PV MAX DU PERSONNAGE
    protected $PA;          //POINTS D'ATTAQUE DU PERSONNAGE
    protected $PD;          //POINTS DE DEFENSE DU PERSONNAGE
    protected $currentExp;   //EXPERIENCE DU PERSONNAGE
    protected $expForNextLevel; //EXPERIENCE NECESSAIRE POUR LE PROCHAIN NIVEAU
    protected $level;    //NIVEAU DU PERSONNAGE

    function __construct($name)     //CONSTRUCTEUR
    {
        $this->name = $name;
        $this->PVmax = 100;
        $this->PV = $this->PVmax;
        $this->PA = 10;
        $this->PD = 3;
        $this->currentExp = 0;
        $this->level = 1;
    }

    // getter

    public function getId() {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getPV()
    {
        return $this->PV;
    }
    public function getPVmax()
    {
        return $this->PVmax;
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
    public function setId($id) {
        $this->id = $id;
    }
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

    //METHODES
    public function attaquer(Personnage $cible)     //METHODE ATTAQUER
    {
        $degats = $this->getPA() - $cible->getPD();
        if ($degats > 0) {
            $cible->PV -= $degats;
        }
    }

    public function GainExp(Int $exp)       //METHODE GAIN D'EXPERIENCE
    {
        $this->currentExp += $exp;
        if ($this->currentExp >= $this->expForNextLevel) {
            $this->level += 1;
            $this->currentExp = 0;
            $this->expForNextLevel = $this->expForNextLevel * 1.5;
        }
    }

    public function isDead()            //METHODE VERIFIE SI LE PERSONNAGE EST MORT
    {
        if ($this->PV <= 0) {
            return true;
        } else {
            return false;
        }
    }

    public function recevoirDegats($degats)     //METHODE RECOIT DES DEGATS
    {
        $this->PV -= $degats;
    }
    public function isLevelUp()     //METHODE VERIFIE SI LE PERSONNAGE A GAGNE UN NIVEAU
    {
        if ($this->currentExp >= $this->expForNextLevel) {
            $this->level += 1;
            $this->PV = $this->PV * 1.5;
            $this->PA = $this->PA * 1.5;
            $this->PD = $this->PD * 1.5;
            $this->expForNextLevel = $this->expForNextLevel * 1.5;
            return true;
        } else {
            return false;
        }
    }

    public function afficherStats()     //METHODE AFFICHE LES STATS DU PERSONNAGE
    {
        echo "Statistiques: \n";
        echo "Nom: " . $this->getName() . "\n";
        echo "Vie: " . $this->getPV() . "\n";
        echo "Niveau: " . $this->getLevel() . "\n";
        echo "Dégâts: " . $this->getPA() . "\n";
        echo "Défense: " . $this->getPD() . "\n";
    }
}

class Joueur extends Personnage         //CLASSE JOUEUR
{
    protected $inventaire;          //INVENTAIRE DU JOUEUR
    protected $or;                  //OR DU JOUEUR

    function __construct($name, $inventaire)        //CONSTRUCTEUR
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

    //METHODES
    public function ajouterObjetInventaire($objet)      //METHODE AJOUTE UN OBJET A L'INVENTAIRE
    {
        $this->inventaire[] = $objet;
    }

    public function setPlayerSave($PV, $PA, $PD, $EXP, $Niveau)     //METHODE DEFINIT LES STATS DU JOUEUR
    {
        $this->PV = $PV;
        $this->PA = $PA;
        $this->PD = $PD;
        $this->currentExp = $EXP;
        $this->level = $Niveau;
    }

    public function supprimerObjetInventaire($objet)        //METHODE SUPPRIME UN OBJET DE L'INVENTAIRE
    {
        $index = array_search($objet, $this->inventaire);

        if ($index !== false) {
            unset($this->inventaire[$index]);
            $this->inventaire = array_values($this->inventaire);
            return true;
        } else {
            return false;
        }
    }

    public function afficherInventaire()        //METHODE AFFICHE L'INVENTAIRE DU JOUEUR
    {
        echo "Inventaire :\n";
        foreach ($this->inventaire as $objet) {
            echo "- " . $objet . "\n";
        }
    }

    public function utiliserObjetInventaire($nomObjet)      //METHODE UTILISE UN OBJET DE L'INVENTAIRE
    {
        if (in_array($nomObjet, $this->inventaire)) {
            echo "Vous utilisez l'objet : " . $nomObjet . ".\n";

            $index = array_search($nomObjet, $this->inventaire);
            unset($this->inventaire[$index]);
        } else {
            echo "L'objet " . $nomObjet . " n'est pas dans votre inventaire.\n";
        }
    }

    public function equiperArme($arme)          //METHODE EQUIPE UNE ARME
    {
        if (in_array($arme, $this->inventaire)) {       //SI L'ARME EST DANS L'INVENTAIRE
            $detailsArme = $this->detailsArme($arme);       //ON RECUPERE LES DETAILS DE L'ARME
            $this->degats += $detailsArme['attaque'];       //ON AJOUTE LES DEGATS DE L'ARME AUX DEGATS DU JOUEUR
            echo "Vous avez équipé l'arme : " . $arme . ".\n";
        } else {
            "Vous ne possédez pas l'arme : " . $arme . ".\n";
        }
    }

    private function detailsArme($nomArme)      //METHODE AFFICHE LES DETAILS D'UNE ARME
    {
        $armes = [
            // "épée1" => ["attaque" => 10],
            // "épée2" => ["attaque" => 15],
        ];

        return $armes[$nomArme];
    }

    public function acheter(Arme $arme)     //METHODE ACHETE UNE ARME
    {
        if ($this->or >= $arme->getPrix()) {        //SI LE JOUEUR A ASSEZ D'OR
            $this->or -= $arme->getPrix();          //ON RETIRE LE PRIX DE L'ARME A L'OR DU JOUEUR
            $this->ajouterObjetInventaire($arme);    //ON AJOUTE L'ARME A L'INVENTAIRE DU JOUEUR
            echo "Vous avez acheté l'arme : " . $arme->getNomObjet() . ".\n";       //ON AFFICHE UN MESSAGE
        } else {
            echo "Vous n'avez pas assez d'or pour acheter l'arme : " . $arme->getNomObjet() . ".\n";
        }
    }

    public function gagnerExp($expGagnee)       //METHODE GAGNE DE L'EXPERIENCE
    {
        $this->currentExp += $expGagnee;
    }

    public function gagnerOr($orGagne)      //METHODE GAGNE DE L'OR
    {
        $this->or += $orGagne;
    }

    public function gagnerObjet($objet)     //METHODE GAGNE UN OBJET
    {
        $this->ajouterObjetInventaire($objet);
    }
}

class Monstre extends Personnage        //CLASSE MONSTRE
{
    protected $level;       //NIVEAU DU MONSTRE
    protected $dropExp;     //EXPERIENCE DONNEE PAR LE MONSTRE
    protected $dropGold;    //OR DONNE PAR LE MONSTRE

    function __construct($name, $PV, $PA, $PD, $level, $dropExp, $dropGold)     //CONSTRUCTEUR
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
    public function getLevel()      
    {
        return $this->level;
    }

    // setter
    public function setLevel($level)
    {
        $this->level = $level;
    }
}

class Objet             //CLASSE OBJET
{
    protected $nomObjet;        //NOM DE L'OBJET    

    protected $typeObjet;       //TYPE D'OBJET

    protected $bonus;           //BONUS DE L'OBJET

    protected $malus;           //MALUS DE L'OBJET

    function __construct($nomObjet, $typeObjet, $bonus, $malus)     //CONSTRUCTEUR
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

    public function getId() {
        return $this->id;
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

    //METHODES
    public function afficherObjet()     //METHODE AFFICHE LES DETAILS DE L'OBJET
    {
        echo "Nom de l'objet : " . $this->nomObjet . ", Type d'objet : " . $this->typeObjet . ", Bonus : " . $this->bonus . ", Malus : " . $this->malus . "\n";
    }

    public function utiliserObjet(Personnage $cible)        //METHODE UTILISE L'OBJET
    {
        if ($this->typeObjet == "arme") {
            $cible->setPA($cible->getPA() + $this->bonus);
            $cible->setPD($cible->getPD() + $this->malus);
        } elseif ($this->typeObjet == "potion") {
            $cible->setPV($cible->getPV() + $this->bonus);
        }
    }
}

class Arme extends Objet        //CLASSE ARME
{

    protected $typeArme;        //TYPE D'ARME
    protected $effet;           //EFFET DE L'ARME
    protected $degats;         //DEGATS DE L'ARME
    protected $nivRequis;       //NIVEAU REQUIS POUR UTILISER L'ARME
    protected $prix;            //PRIX DE L'ARME
    function __construct($nomObjet, $typeObjet, $bonus, $malus, $typeArme, $degats, $effet, $nivRequis, $prix)      //CONSTRUCTEUR
    {
        parent::__construct($nomObjet, $typeObjet, $bonus, $malus);
        $this->typeArme = $typeArme;
        $this->effet = $effet;
        $this->degats = $degats;
        $this->nivRequis = $nivRequis;
        $this->prix = $prix;
    }

    //getter
    public function getDegats()
    {
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

    public function getNivRequis()
    {
        return $this->nivRequis;
    }

    public function getPrix()
    {
        return $this->prix;
    }
    //setter
    public function setDegats($degats)
    {
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

    public function setNivRequis($nivRequis)
    {
        $this->nivRequis = $nivRequis;
    }

    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    public function afficherArme()      //METHODE AFFICHE LES DETAILS DE L'ARME
    {
        echo "Nom de l'arme : " . $this->nomObjet . ", Type d'arme : " . $this->typeArme . ", Bonus : " . $this->bonus . ", Malus : " . $this->malus . ", Dégats : " . $this->degats . ", Effet : " . $this->effet . ", Niveau Requis : " . $this->nivRequis . "\n";
    }

    public function utiliserObjet(Personnage $cible)        //METHODE UTILISE L'ARME
    {
        if ($this->typeObjet == "arme") {
            $cible->setPA($cible->getPA() + $this->bonus);
            $cible->setPD($cible->getPD() + $this->malus);
        }
    }

    public function __toString()        //METHODE AFFICHE LES DETAILS DE L'ARME ET TRANSFORME LES DONNEES EN STRING
    {
        return "Nom de l'arme : " . $this->nomObjet . ", Type d'arme : " . $this->typeArme . ", Bonus : " . $this->bonus . ", Malus : " . $this->malus . ", Dégats : " . $this->degats . ", Effet : " . $this->effet . ", Prix : " . $this->prix . ", Niveau Requis : " . $this->nivRequis;
    }
}

class Inventaire        //CLASS INVENTAIRE
{
    protected $arme;    //ARME DE L'INVENTAIRE

    function __construct($arme)    //CONSTRUCTEUR
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
}