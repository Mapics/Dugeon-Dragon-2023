<?php

abstract class Personnage
{
    // On met les variables en protected car elles vont être utilisées par les classes filles
    protected string $nom;
    protected int $vie;
    protected int $degats;

    protected function __construct($nom, $vie, $degats)
    {
        $this->nom = $nom;
        $this->vie = $vie;
        $this->degats = $degats;
    }
}

class Gentil extends Personnage
{
    // On met les variables en privés car on veut pouvoir les utiliser que dans cette classe
    private int $niveau;
    private int $experience;
    private $pouvoirs = array();
    private $pouvoirsDebloquer = array();
    private $inventaire;

    public function __construct($nom, $vie, $degats)
    {
        parent::__construct($nom, $vie, $degats);
        $this->niveau = 1;
        $this->experience = 0;
        $this->pouvoirsDebloquer = ["Kamehaha", "Genkidama"];
        $this->pouvoirs = ["Coup de poing"];
        $this->inventaire = array("Senzu" => 0, "Capsule boost" => 0);
    }

    // GETTERS
    public function getNom()
    {
        return $this->nom;
    }
    public function getVie()
    {
        return $this->vie;
    }
    public function getDegats()
    {
        return $this->degats;
    }
    public function getPouvoirs()
    {
        return $this->pouvoirs;
    }
    public function getNiveau()
    {
        return $this->niveau;
    }
    public function getExperience()
    {
        return $this->experience;
    }
    public function getInventaire()
    {
        return $this->inventaire;
    }

    // SETTERS
    public function setVie($nouveauVie)
    {
        $this->vie = $nouveauVie;
    }
    public function setExperience($nouveauExperience)
    {
        $this->experience = $nouveauExperience;
    }
    public function setNiveau($nouveauNiveau)
    {
        $this->niveau = $nouveauNiveau;
    }
    public function setDegats($nouveauDegats)
    {
        $this->degats = $nouveauDegats;
    }
    public function setPouvoirs($nouveauPouvoirs)
    {
        $this->pouvoirs = $nouveauPouvoirs;
    }
    public function ajouterObjetInventaire($objet)
    {
        $this->inventaire[$objet]++;
    }
    public function supprimerObjetInventaire($objet)
    {
        $this->inventaire[$objet]--;
    }

    // METHODS
    public function choisirAttaque()
    {
        // On regarde le nombre d'attaque que le personnage a
        $listePouvoirs = count($this->getPouvoirs());
        switch ($listePouvoirs) {
                // Si le personnage a 1 attaque, on lui propose de l'utiliser
            case 1:
                clearTerminal();
                $choix = (int)readline("Quelle attaque?\n1. Coup de poing");
                // VERIF
                while ($choix != 1) {
                    clearTerminal();
                    echo "Erreur, merci de choisir une attaque à l'aide des chiffres du clavier.\n";
                    $choix = (int)readline("Quelle attaque?\n1. Coup de poing");
                }
                return $choix;
                // Si le personnage a 2 attaques, on lui propose de les utiliser
            case 2:
                clearTerminal();
                $choix = (int)readline("Quelle attaque?\n1. Coup de poing \n2. Kamehaha");
                // VERIF
                while ($choix < 1 || $choix > 2) {
                    $choix = (int)readline("Quelle attaque?\n1. Coup de poing \n2. Kamehaha");
                }
                return $choix;
                // Si le personnage a 3 attaques, on lui propose de les utiliser
            case 3:
                clearTerminal();
                $choix = (int)readline("Quelle attaque?\n1. Coup de poing \n2. Kamehaha \n3. Genkidama");
                // Verif
                while ($choix < 1 || $choix > 3) {
                    $choix = (int)readline("Quelle attaque?\n1. Coup de poing \n2. Kamehaha \n3. Genkidama");
                }
                clearTerminal();
                return $choix;
        }
    }

    public function attaquer($pouvoirChoisi)
    {
        // On regarde le pouvoir choisi par le joueur et on multiplie les dégâts par le chiffre correspondant en fonction de la puissance de l'attaque
        switch ($pouvoirChoisi) {
            case 1:
                // COUP DE POING MULTIPLIE LES DEGATS INFLIGES PAR 1
                return 1 * $this->getDegats();
            case 2:
                // KAMEHAHA MULTIPLIE LES DEGATS INFLIGES PAR 2 ETC
                return 2 * $this->getDegats();
            case 3:
                // GENKIDAMA MULTIPLIE LES DEGATS INFLIGES PAR 3 ETC
                return 3 * $this->getDegats();
        }
    }

    public function recevoirDegats($degats)
    {
        // On enlève les dégâts reçus à la vie du personnage
        $this->setVie($this->getVie() - $degats);
        // Si la vie du personnage est inférieure à 1, le personnage est mort et le jeu s'arrete
        if ($this->vie < 1) {
            echo "Vous avez perdu.\n";
            $this->finDeJeu();
            exit;
        }
        // Sinon, on affiche les points de vie restants
        else {
            echo $this->getNom() . " a encore " . $this->getVie() . " points de vie.\n";
        }
    }

    public function gagnerExperience($experienceGagnee)
    {
        // On ajoute l'expérience gagnée à l'expérience du personnage
        $this->setExperience($this->getExperience() + $experienceGagnee);
        echo $this->getNom() . " a " . $this->getExperience() . " points d'expérience.\n";
        // On vérifie si le personnage a passer un niveau grâce à la méthode gagnerNiveau()
        $this->gagnerNiveau();
    }

    public function gagnerNiveau()
    {
        // On regarde le niveau du personnage et on lui fait gagner un niveau si il a assez d'expérience
        // En fonction du niveau du personnage, il lui faut de plus en plus d'expérience pour passer un niveau
        switch ($this->getNiveau()) {
            case 1:
                if ($this->getExperience() >= 10) {
                    echo "Vous avez gagné un niveau.\n";
                    echo "Vous êtes niveau 2.\n";
                    // GAGNER UN NIVEAU
                    $this->setNiveau($this->getNiveau() + 1);
                    // RESET L'EXPERIENCE
                    $this->setExperience($this->getExperience() - 10);
                    // MODIFIER STATS
                    $this->setVie($this->getVie() * 2);
                    $this->setDegats($this->getDegats() + 5);
                }
                break;
            case 2:
                if ($this->getExperience() >= 20) {
                    echo "Vous avez gagné un niveau.\n";
                    echo "Vous êtes niveau 3.\n";
                    echo "Vous avez débloqué le nouveau pouvoir Kamehameha.\n";
                    $this->setNiveau($this->getNiveau() + 1);
                    $this->setExperience($this->getExperience() - 20);
                    $this->setVie($this->getVie() * 2);
                    $this->setDegats($this->getDegats() + 5);
                    // DEBLOQUER NOUVEAU POUVOIR AU NIVEAU 3
                    array_push($this->pouvoirs, $this->pouvoirsDebloquer[0]);
                }
                break;
            case 3:
                if ($this->getExperience() >= 30) {
                    echo "Vous avez gagné un niveau.\n";
                    echo "Vous êtes niveau 4.\n";
                    $this->setNiveau($this->getNiveau() + 1);
                    $this->setExperience($this->getExperience() - 30);
                    $this->setVie($this->getVie() * 2);
                    $this->setDegats($this->getDegats() + 5);
                }
                break;
            case 4:
                if ($this->getExperience() >= 40) {
                    echo "Vous avez gagné un niveau.\n";
                    echo "Vous êtes niveau 5.\n";
                    echo "Vous avez débloqué le nouveau pouvoir Genkidama.\n";
                    $this->setNiveau($this->getNiveau() + 1);
                    $this->setExperience($this->getExperience() - 40);
                    $this->setVie($this->getVie() * 2);
                    $this->setDegats($this->getDegats() + 5);
                    // DEBLOQUER NOUVEAU POUVOIR AU NIVEAU 5
                    array_push($this->pouvoirs, $this->pouvoirsDebloquer[1]);
                }
                break;
            case 5:
                if ($this->getExperience() >= 50) {
                    echo "Vous avez gagné un niveau.\n";
                    echo "Vous êtes niveau 6.\n";
                    $this->setNiveau($this->getNiveau() + 1);
                    $this->setExperience($this->getExperience() - 50);
                    $this->setVie($this->getVie() * 2);
                    $this->setDegats($this->getDegats() + 5);
                }
                break;
            default:
                break;
        }
    }

    public function afficherInfos()
    {
        // Fonction qui affiche les statistiques du personnage
        echo "Statistiques: \n";
        echo "Nom: " . $this->getNom() . "\n";
        echo "Vie: " . $this->getVie() . "\n";
        echo "Niveau: " . $this->getNiveau() . "\n";
        echo "Dégâts: " . $this->getDegats() . "\n";
        echo "Pouvoirs: \n";
        for ($i = 0; $i < count($this->getPouvoirs()); $i++) {
            echo "- " . $this->getPouvoirs()[$i] . "\n";
        }
    }

    public function finDeJeu()
    {
        // Fonction qui s'execute quand le personnage meurt
        // On supprime la sauvegarde dans ce cas
        echo "JEU FINI";
        unlink("save.txt");
        exit;
    }

    public function afficherInventaire()
    {
        // Fonction qui affiche l'inventaire du personnage
        echo "Inventaire: \n";
        foreach ($this->getInventaire() as $key => $value) {
            echo "- " . $key . " : " . $value . "\n";
        }
    }

    public function utiliserObjetInventaire()
    {
        // Fonction qui permet d'utiliser un objet de l'inventaire
        // On demande au joueur quel objet il veut utiliser
        $choix = (int)readline("Quel objet voulez vous utiliser?\n1. Senzu\n2. Capsule boost\n");
        // VERIF
        while ($choix < 1 || $choix > 2) {
            $choix = (int)readline("Quel objet voulez vous utiliser?\n1. Senzu\n2. Capsule boost\n");
        }
        switch ($choix) {
                // Si le joueur veut utiliser un Senzu, on vérifie qu'il en a un dans son inventaire
                // Si il en a un, on lui rajoute 10 points de vie et on supprime le Senzu de son inventaire
            case 1:
                if ($this->getInventaire()["Senzu"] > 0) {
                    $this->setVie($this->getVie() + 10);
                    $this->supprimerObjetInventaire("Senzu");
                    echo "Vous avez utilisé un Senzu\n";
                    echo "Vous avez gagné 10 points de vie\n";
                } else {
                    echo "Vous n'avez pas de Senzu\n";
                }
                break;
                // Si le joueur veut utiliser une Capsule boost, on vérifie qu'il en a une dans son inventaire
                // Si il en a une, on lui rajoute 5 points de dégâts et on supprime la Capsule boost de son inventaire
            case 2:
                if ($this->getInventaire()["Capsule boost"] > 0) {
                    $this->setDegats($this->getDegats() + 5);
                    $this->supprimerObjetInventaire("Capsule boost");
                    echo "Vous avez utilisé une Capsule boost\n";
                    echo "Vous avez gagné 5 points de dégâts\n";
                } else {
                    echo "Vous n'avez pas de Capsule boost\n";
                }
                break;
            default:
                break;
        }
    }
}


class Mechant extends Personnage
{
    // On met les variables en privés car on veut pouvoir les utiliser que dans cette classe
    private $pouvoirs = array();
    private int $experience;
    protected bool $estMort;

    public function __construct($nom, $vie, $degats, $pouvoirs, $experience)
    {
        parent::__construct($nom, $vie, $degats);
        $this->pouvoirs = $pouvoirs;
        $this->experience = $experience;
        $this->estMort = false;
    }

    // GETTERS
    public function getNom()
    {
        return $this->nom;
    }
    public function getVie()
    {
        return $this->vie;
    }
    public function getDegats()
    {
        return $this->degats;
    }
    public function getPouvoirs()
    {
        return $this->pouvoirs;
    }
    public function getExperience()
    {
        return $this->experience;
    }
    public function getEstMort()
    {
        return $this->estMort;
    }

    // SETTERS
    public function setVie($nouveauVie)
    {
        $this->vie = $nouveauVie;
    }
    public function setEstMort()
    {
        $this->estMort = true;
    }

    // METHODS
    public function choisirAttaque()
    {
        // Fonction qui renvoie un chiffre aléatoire entre 0 et le nombre d'attaques que l'ennemi a de façon à ce qu'il choisisse une attaque aléatoire
        $choix = rand(0, count($this->getPouvoirs()) - 1);
        return $choix;
    }

    public function attaquer($pouvoirChoisi)
    {
        // On regarde le pouvoir choisi par l'ennemi et on multiplie les dégâts par le chiffre correspondant en fonction de la puissance de l'attaque
        switch ($pouvoirChoisi) {
            case 0:
                // COUP DE POING MULTIPLIE LES DEGATS INFLIGES PAR 1
                return 1 * $this->getDegats();
            case 1:
                // COUP DE PIED MULTIPLIE LES DEGATS INFLIGES PAR 2 ETC
                return 2 * $this->getDegats();
            case 2:
                // COUP DE BIDON ATTACK MULTIPLIE LES DEGATS INFLIGES PAR 2 ETC
                return 2 * $this->getDegats();
            case 3:
                // BIG BANG ATTACK MULTIPLIE LES DEGATS INFLIGES PAR 3 ETC
                return 3 * $this->getDegats();
            case 4:
                // CRUSH CANNON MULTIPLIE LES DEGATS INFLIGES PAR 4 ETC
                return 4 * $this->getDegats();
        }
    }

    public function recevoirDegats($degats)
    {
        // On enlève les dégâts reçus à la vie de l'ennemi
        $this->setVie($this->getVie() - $degats);
        // Si la vie de l'ennemi est inférieure à 1, l'ennemi est mort
        if ($this->vie < 1) {
            clearTerminal();
            echo $this->getNom() . " est mort. \n";
            // On met la variable estMort à true
            $this->setEstMort();
        }
        // Sinon, on affiche les points de vie restants
        else {
            clearTerminal();
            echo $this->getNom() . " a encore " . $this->getVie() . " points de vie \n";
        }
    }
}

function combat(Gentil $personnage, $listeEnnemis)
{
    // Fonction qui permet de lancer un combat entre le personnage et un ou plusieurs ennemis
    // On boucle tant que le personnage est en vie et qu'il reste des ennemis
    while ($personnage->getVie() > 0 || count($listeEnnemis) < 1) {
        // Si il n'y a qu'un seul ennemi
        if (count($listeEnnemis) == 1) {
            echo "Vous vous battez contre " . $listeEnnemis[0]->getNom() . ".\n";
            // TOUR DU JOUEUR
            $choix = (int)readline("1. Attaquer 2. Voir statistiques 3. Utiliser un objet\n");
            // VERIF
            while ($choix < 1 || $choix > 3) {
                clearTerminal();
                echo "Erreur, merci de saisir un chiffre entre 1 et 3.\n";
                echo "Vous vous battez contre " . $listeEnnemis[0]->getNom() . ".\n";
                $choix = (int)readline("1. Attaquer 2. Voir statistiques 3. Utiliser un objet\n");
            }
            switch ($choix) {
                    // TOUR D'ATTAQUE
                case 1:
                    // On récupère le choix d'attaque du joueur dans une variable
                    $choixAttaque = $personnage->choisirAttaque();

                    // On récupere les degats infligés par le personnage dans une variable
                    $degatsInfliges = $personnage->attaquer(($choixAttaque));

                    // On enlève les dégâts infligés à l'ennemi
                    $listeEnnemis[0]->recevoirDegats($degatsInfliges);

                    // On vérifie si l'ennemi est mort
                    if ($listeEnnemis[0]->getEstMort() == true) {
                        // Si c'est le cas, on ajoute l'expérience gagnée par le personnage
                        $personnage->gagnerExperience($listeEnnemis[0]->getExperience());

                        // Le personnage a une chance de gagner un objet à chaque fois qu'un ennemi est tué
                        $chance = rand(1, 5);
                        if ($chance == 1) {
                            $personnage->ajouterObjetInventaire("Senzu");
                            echo "Vous avez gagné un Senzu\n";
                        } elseif ($chance == 2) {
                            $personnage->ajouterObjetInventaire("Capsule boost");
                            echo "Vous avez gagné une Capsule boost\n";
                        }
                        // On supprime l'ennemi de la liste
                        unset($listeEnnemis[0]);
                        echo "Le combat est fini.\n";
                        clearTerminalPlayerInput();
                        return;
                    }
                    // TOUR DE L'ENNEMI
                    // On récupère le choix d'attaque de l'ennemi dans une variable
                    $choixAttaqueEnnemi = $listeEnnemis[0]->choisirAttaque();

                    echo $listeEnnemis[0]->getNom() . " choisi l'attaque " . $listeEnnemis[0]->getPouvoirs()[$choixAttaqueEnnemi] . "\n";

                    // On récupere les degats infligés par l'ennemi dans une variable
                    $degatsInfligesEnnemi = $listeEnnemis[0]->attaquer($choixAttaqueEnnemi);

                    // On enlève les dégâts infligés au personnage
                    $personnage->recevoirDegats($degatsInfligesEnnemi);
                    break;
                case 2:
                    // Si le joueur veut voir ses statistiques, on les affiche
                    clearTerminal();
                    $personnage->afficherInfos();
                    clearTerminalPlayerInput();
                    break;
                case 3:
                    // Si le joueur veut utiliser un objet, on affiche son inventaire et on lui propose d'utiliser un objet
                    clearTerminal();
                    $personnage->afficherInventaire();
                    $personnage->utiliserObjetInventaire();
                    clearTerminalPlayerInput();
                    break;
            }
        }
        // ²Si il y a plusieurs ennemis
        else {
            // TOUR DU JOUEUR

            $choix = (int)readline("1. Attaquer 2. Voir statistiques 3. Utiliser un objet\n");
            // VERIF
            while ($choix < 1 || $choix > 3) {
                $choix = (int)readline("1. Attaquer 2. Voir statistiques 3. Utiliser un objet\n");
            }
            switch ($choix) {
                    // TOUR D'ATTAQUE
                case 1:
                    // On affiche la liste des ennemis encore en vie
                    echo "Voici la liste des ennemis:\n";
                    for ($i = 0; $i < count($listeEnnemis); $i++) {
                        echo $i + 1 . ". " . $listeEnnemis[$i]->getNom() . "(" . $listeEnnemis[$i]->getVie() . " pv)\n";
                    }

                    // On demande au joueur quel ennemi il veut attaquer
                    $choixEnnemi = (int)readline("Quel ennemi?\n") - 1;
                    // VERIF
                    while ($choixEnnemi < 0 or $choixEnnemi > 1) {
                        clearTerminal();
                        echo "Voici la liste des ennemis:\n";
                        for ($i = 0; $i < count($listeEnnemis); $i++) {
                            echo $i + 1 . ". " . $listeEnnemis[$i]->getNom() . "(" . $listeEnnemis[$i]->getVie() . " pv)\n";
                        }
                        $choixEnnemi = (int)readline("Quel ennemi?\n") - 1;
                    }
                    // On récupère le choix d'attaque du joueur dans une variable
                    $choixAttaque = $personnage->choisirAttaque();

                    // On récupere les degats infligés par le personnage dans une variable
                    $degatsInfliges = $personnage->attaquer(($choixAttaque));

                    // On enlève les dégâts infligés à l'ennemi
                    $listeEnnemis[$choixEnnemi]->recevoirDegats($degatsInfliges);

                    // On vérifie si l'ennemi est mort
                    if ($listeEnnemis[$choixEnnemi]->getEstMort() == true) {
                        // Si c'est le cas, on ajoute l'expérience gagnée par le personnage
                        $personnage->gagnerExperience($listeEnnemis[$choixEnnemi]->getExperience());

                        // Le personnage a une chance de gagner un objet à chaque fois qu'un ennemi est tué
                        $chance = rand(1, 5);
                        if ($chance == 1) {
                            $personnage->ajouterObjetInventaire("Senzu");
                            echo "Vous avez gagné un Senzu.\n";
                        } elseif ($chance == 2) {
                            $personnage->ajouterObjetInventaire("Capsule boost");
                            echo "Vous avez gagné une Capsule boost.\n";
                        }
                        unset($listeEnnemis[$choixEnnemi]);
                        $listeEnnemis = array_values($listeEnnemis);
                    }
                    // TOUR DE L'ENNEMI
                    echo "Les ennemis se concertent pour savoir qui va attaquer!\n";
                    sleep(2);
                    // On choisit un ennemi aléatoire qui va attaquer
                    $rand = rand(0, count($listeEnnemis) - 1);
                    $choixAttaqueEnnemi = $listeEnnemis[$rand]->choisirAttaque();
                    clearTerminal();
                    echo $listeEnnemis[$rand]->getNom() . " choisi l'attaque " . $listeEnnemis[$rand]->getPouvoirs()[$choixAttaqueEnnemi] . "\n";
                    clearTerminalPlayerInput();
                    $degatsInfligesEnnemi = $listeEnnemis[$rand]->attaquer($choixAttaqueEnnemi);
                    $personnage->recevoirDegats($degatsInfligesEnnemi);
                    break;
                case 2:
                    // Si le joueur veut voir ses statistiques, on les affiche
                    clearTerminal();
                    $personnage->afficherInfos();
                    clearTerminalPlayerInput();
                    break;
                case 3:
                    // Si le joueur veut utiliser un objet, on affiche son inventaire et on lui propose d'utiliser un objet
                    clearTerminal();
                    $personnage->afficherInventaire();
                    $personnage->utiliserObjetInventaire();
                    clearTerminalPlayerInput();
                    break;
            }
        }
    }
}

function ennemiAleatoire($nombreEnnemis)
{
    // Fonction qui génère un ou deux index aléatoires pour choisir un ou deux ennemis
    // On met ces index dans une liste pour pouvoir récupérer potentiellement plusieurs valeur dans le return
    $listeIndexEnnemi = array();
    switch ($nombreEnnemis) {
            // Si le nombre d'ennemis est 1, on génère un index aléatoire entre 0 et 3
        case 1:
            $ennemi = rand(0, 3);
            // On met l'index dans la liste et on la retourne
            array_push($listeIndexEnnemi, $ennemi);
            return $listeIndexEnnemi;
            // Si le nombre d'ennemis est 2, on génère deux index aléatoires entre 0 et 3
        case 2:
            $ennemi1 = rand(0, 3);
            $ennemi2 = rand(0, 3);
            // NE PAS GENERER DEUX FOIS LE MEME ENNEMI
            while ($ennemi2 == $ennemi1) {
                $ennemi2 = rand(0, 3);
            }
            array_push($listeIndexEnnemi, $ennemi1, $ennemi2);
            return $listeIndexEnnemi;
    }
}


function creerPersonnage()
{
    // Fonction qui permet de choisir son personnage et de le retourner

    // On initialise des personnages par défaut
    $goku = new Gentil("Goku", 10, 3);
    $piccolo = new Gentil("Piccolo", 8, 4);
    $yamcha = new Gentil("Yamcha", 5, 2);

    // On demande à l'utilisateur d'en choisir un
    echo "Choisir un personnage :\n";
    // VERIF
    $choix = (int)readline("1. Goku \n2. Piccolo \n3. Yamcha\n");
    while ($choix < 1 or $choix > 3) {
        clearTerminal();
        echo "Erreur, merci de saisir un chiffre entre 1 et 3.\nChoisir un personnage :\n";
        $choix = (int)readline("1. Goku \n2. Piccolo \n3. Yamcha\n");
    }
    switch ($choix) {
        case 1:
            clearTerminal();
            return $goku;
        case 2:
            clearTerminal();
            return $piccolo;
        case 3:
            clearTerminal();
            return $yamcha;
    }
}

function sauvegarder($personnage, $cmptVictoires)
{
    // Fonction qui permet de récupérer les informations de la partie dans un fichier "save.txt"
    // Elle prend en argument le personnage (l'objet) et le compteur de victoires car ce sont les seules informations qui changent durant la partie

    // On met les informations de la partie dans une liste
    $listeDeDonnees = array(
        $personnage->getNom(),
        $personnage->getVie(),
        $personnage->getDegats(),
        $personnage->getExperience(),
        $personnage->getNiveau(),
        $personnage->getPouvoirs(),
        $cmptVictoires
    );
    // On sérialize la liste pour pouvoir la mettre dans le fichier
    $listeDeDonnees = serialize($listeDeDonnees);
    // On met la liste dans le fichier
    file_put_contents("save.txt", $listeDeDonnees);
    echo "Partie sauvegardée\n";
    exit;
}

function chargerSauvegarde()
{
    // Fonction qui permet de charger une partie sauvegardée à partir d'un fichier "save.txt"

    // On récupère les informations du fichier
    $listeDeDonnees = file_get_contents("save.txt");
    // On unserialize la liste pour pouvoir la réutiliser
    $listeDeDonnees = unserialize($listeDeDonnees);
    // On recrée le personnage avec les informations du fichier
    $heros = new Gentil($listeDeDonnees[0], $listeDeDonnees[1], $listeDeDonnees[2]);
    $heros->setExperience($listeDeDonnees[3]);
    $heros->setNiveau($listeDeDonnees[4]);
    $heros->setPouvoirs($listeDeDonnees[5]);
    echo "Partie chargée\n";
    // On renvoit le personnage (objet) et le compteur de victoires (int) dans une liste pour avoir plusieurs valeurs dans le return
    return array($heros, $listeDeDonnees[6]);
}

function clearTerminal()
{
    // Fonction qui permet de clear le terminal
    popen("cls", "w");
}

function clearTerminalPlayerInput()
{
    // Fonction qui permet de clear le terminal lorsque le joueur appui sur entrée
    $entree = readline("Appuyer sur entrée pour continuer...");
    while ($entree != "") {
        $entree = readline("Appuyer sur entrée pour continuer...");
    }
    popen("cls", "w");
}

function jeu($heros, $cmptVictoires)
{
    // Fonction qui lance la partie
    clearTerminal();
    echo "Votre objectif est de gagner 10 combats de suite.\nBonne chance!\n";
    clearTerminalPlayerInput();
    echo "Vous devez encore gagner " . 11 - $cmptVictoires . " combats.\n";
    // Tant que le joueur n'a pas gagné 10 combats ou qu'il n'est pas mort, on lance un combat
    while ($cmptVictoires < 11) {
        // On crée des listes d'ennemis
        echo "Le combat numéro " . $cmptVictoires . " commence.\n";
        $listeMechantsNiveau1 = array(
            new Mechant("Saibaman", rand(1, 3), rand(1, 3), ["Coup de poing", "Coup de pied"], 5),
            new Mechant("Raditz", rand(3, 5), rand(3, 5), ["Coup de poing", "Coup de pied"], 5),
            new Mechant("Nappa", rand(3, 5), rand(3, 5), ["Coup de poing", "Coup de pied"], 5),
            new Mechant("Reacum", rand(3, 5), rand(3, 5), ["Coup de poing", "Coup de pied", "Coup de bidon"], 5),
        );

        $listeMechantsNiveau2 = array(
            new Mechant("C17", rand(1, 3), rand(1, 3), ["Coup de poing", "Coup de pied"], 5),
            new Mechant("C18", rand(3, 5), rand(3, 5), ["Coup de poing", "Coup de pied"], 5),
            new Mechant("Freezer", rand(3, 5), rand(3, 5), ["Coup de poing", "Coup de pied", "Big Bang Attack"], 5),
            new Mechant("Vegeta", rand(3, 5), rand(3, 5), ["Coup de poing", "Coup de pied", "Big Bang Attack"], 5),
        );
        // On lance un combat en fonction du nombre de victoires du joueur
        switch ($cmptVictoires) {
                // Si le joueur a moins de 5 victoires, on lance un combat contre un ennemi de niveau 1
            case $cmptVictoires < 5:
                // On choisit un ennemi aléatoire
                $listeIndexEnnemi = ennemiAleatoire(1);

                // On lance le combat
                combat($heros, array($listeMechantsNiveau1[$listeIndexEnnemi[0]]));

                // On ajoute une victoire au compteur si le joueur a gagné
                $cmptVictoires++;

                // PROPOSITION DE CONTINUER/SAUVEGARDER
                clearTerminal();
                $choix = strtolower(readline("Que voulez vous faire?\nContinuer (c)\nSauvegarder (s)"));
                // VERIF
                while ($choix != "c" and $choix != "s") {
                    clearTerminal();
                    echo 'Erreur, merci de saisir "c" ou "s".' . "\n";
                    $choix = strtolower(readline("Que voulez vous faire?\nContinuer (c)\nSauvegarder (s)"));
                }
                // Si je joueur veut sauvegarder, on lance la fonction sauvegarder()
                if ($choix == "s") {
                    sauvegarder($heros, $cmptVictoires);
                }
                clearTerminal();
                break;
                // Si le joueur a entre 5 et 10 victoires, on lance un combat contre deux ennemis de niveau 2
            case $cmptVictoires >= 5:
                // On choisit deux ennemis aléatoires
                $listeIndexEnnemi = ennemiAleatoire(2);
                // On lance le combat
                combat($heros, array($listeMechantsNiveau2[$listeIndexEnnemi[0]], $listeMechantsNiveau2[$listeIndexEnnemi[1]]));
                // On ajoute une victoire au compteur si le joueur a gagné
                $cmptVictoires++;

                // PROPOSITION DE CONTINUER/SAUVEGARDER
                clearTerminal();
                $choix = strtolower(readline("Que voulez vous faire?\nContinuer (c)\nSauvegarder (s)"));
                while ($choix != "c" and $choix != "s") {
                    clearTerminal();
                    echo 'Erreur, merci de saisir "c" ou "s".' . "\n";
                    $choix = strtolower(readline("Que voulez vous faire?\nContinuer (c)\nSauvegarder (s)"));
                }
                if ($choix == "s") {
                    sauvegarder($heros, $cmptVictoires);
                }
                break;
            default:
                echo "Vous avez gagné.";
                exit;
        }
    }
    // Si le joueur a gagné 10 combats, il a gagné la partie et on appelle la fonction finDeJeu()
    $heros->finDeJeu();
}

// MAIN

clearTerminal();
// On demande au joueur s'il veut commencer une nouvelle partie ou charger une partie
$nouvellePartie = strtolower(readline("Nouvelle partie (n)\nCharger partie (c)\n"));
clearTerminal();
// VERIF
while ($nouvellePartie != "n" and $nouvellePartie != "c") {
    clearTerminal();
    echo 'Erreur, merci de saisir "n" ou "c".' . "\n";
    $nouvellePartie = strtolower(readline("Nouvelle partie (n)\nCharger partie (c)\n"));
    clearTerminal();
}
// Si je joueur essaye de charger une partie mais qu'il n'y en avait pas en cours, on lui dit qu'il va commencer une nouvelle partie
if ($nouvellePartie == "c" && filesize("save.txt") == 0) {
    clearTerminal();
    echo "Aucune sauvegarde n'a été trouvée. \nVous allez commencer une nouvelle partie.\n";
    clearTerminalPlayerInput();
    $nouvellePartie = "n";
}
// Si le joueur veut commencer une nouvelle partie, on lance la fonction jeu() avec un nouveau personnage
if ($nouvellePartie == "n") {
    jeu(creerPersonnage(), 1);
}
// Si le joueur veut charger une partie
else {
    // On récupère les informations de la partie dans une liste
    $lst = chargerSauvegarde();
    // On lance la fonction jeu() avec le personnage et le compteur de victoires récupérés
    $personnage = $lst[0];
    $cmptVictoires = $lst[1];
    jeu($personnage, $cmptVictoires);
}
