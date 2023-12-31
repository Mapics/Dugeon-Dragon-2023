<?php
class DD_DAO
{
    // VARIABLE POUR CONNEXION A LA BASE DE DONNEES
    protected $bdd;

    public function __construct($bdd)
    {
        // POUR CONNEXION A LA BASE DE DONNEES
        $this->bdd = $bdd;
    }

    // AJOUTER UN JOUEUR A LA BASE DE DONNEES
    public function ajouterJoueurBDD(Joueur $joueur)
    {
        try {
            $requete = $this->bdd->prepare("INSERT INTO personnages (nom, PV, PA, PD, EXP, Niveau) VALUES (?, ?, ?, ?, ?, ?)");
            $requete->execute([$joueur->getName(), $joueur->getPV(), $joueur->getPA(), $joueur->getPD(), $joueur->getCurrentEXP(), $joueur->getLevel()]);

            $joueur->setId($this->bdd->lastInsertId());
            return true;
        } catch (PDOException $e) {
            echo "Erreur d'ajout de personnage: " . $e->getMessage();
            return false;
        }
    }

    // AJOUTE L'INVENTAIRE LIE AU JOUEUR A LA BASE DE DONNEE
    public function ajouterInventaireBDD(Arme $arme, Personnage $joueur)
    {
        try {
            // INSERE UNE NOUVELLE LIGNE DANS LA TABLE INVENTAIRE EN FONCTION DE L'IDENTIFIANT DU JOUEUR ET SON ARME
            $requete = $this->bdd->prepare("INSERT INTO inventaire (Id_arme, Id_perso) VALUES (?, ?)");
            $requete->execute([$this->getArmeIDfromName($arme->getNomObjet()), $joueur->getId()]);
            return true;
        } catch (PDOException $e) {
            // SI ERREUR
            echo "Erreur d'ajout d'inventaire: " . $e->getMessage();
            return false;
        }
    }

    // VERIFIE SI LE JOUEUR DESIRE SE TROUVE DANS LA BASE DE DONNEES
    public function verifJoueurExistant($player_name, $PV, $PA, $PD, $EXP, $Niveau)
    {
        try {
            // REQUETE POUR RECHERCHER LE JOUEUR PAR SON NOM CHOISI A LA CREATION
            $requete = $this->bdd->prepare("SELECT * FROM personnages WHERE nom = ?");
            $requete->execute([$player_name]);
            $result = $requete->fetchAll(PDO::FETCH_ASSOC);

            // CREE UN OBJET JOUEUR EN FONCTION DU NOM RECUPERE DANS LA BASE DE DONNEE AVEC SON INVENTAIRE VIDE
            $player = new Joueur($result['nom'], array());

            // PREPARE LA SAUVEGARDE DU JOUEUR
            $player->setPlayerSave($PV, $PA, $PD, $EXP, $Niveau);

            // SI LE JOUEUR EXISTE
            if (count($result) > 0) {
                // RETOURNE LE JOUEUR
                return $player;
                // SI LE JOUEUR N'EXISTE PAS
            } else {
                return NULL;
            }
            // SI ERREUR
        } catch (PDOException $e) {
            echo "Erreur de recherche de personnage: " . $e->getMessage();
            return NULL;
        }
    }

    // CRUD ARME EXEMPLE
    public function ajouterArme($nom, $degat, $niv_requis)
    {
        $prep = "INSERT INTO armes (Nom_arme, Degat, Niv_requis) VALUES (?, ?, ?)";
        $requete = $this->bdd->prepare($prep);
        $requete->execute([$nom, $degat, $niv_requis]);
    }

    public function getArmeById($id)
    {
        $prep = "SELECT * FROM armes WHERE Id_arme = ?";
        $requete = $this->bdd->prepare($prep);
        $requete->execute([$id]);
        return $requete->fetch();
    }

    public function modifierArme($id, $nom, $degat, $niv_requis)
    {
        $prep = "UPDATE armes SET Nom_arme = ?, Degat = ?, Niv_requis = ? WHERE Id_arme = ?";
        $requete = $this->bdd->prepare($prep);
        $requete->execute([$nom, $degat, $niv_requis, $id]);
    }

    public function supprimerArme($id)
    {
        $prep = "DELETE FROM armes WHERE Id_arme = ?";
        $requete = $this->bdd->prepare($prep);
        $requete->execute([$id]);
    }

    // GENERER UNE SALLE ALEATOIRE PARMI COMBAT, VIDE, BOSS, MARCHAND, ENIGME
    public function salleAleatoire()
    {
        $rand = rand(1, 100);
        switch (true) {
                // 50% SALLE COMBAT
            case $rand < 50:
                try {
                    // CHOISIR UN MONSTRE ALEATOIRE DE LA BASE DE DONNEES DANS LA TABLE MONSTRE AVEC TYPE NORMAL
                    $newMonstre = $this->bdd->prepare("SELECT * FROM Monstre WHERE Type = 'normal' ORDER BY RAND() LIMIT 1");
                    $newMonstre->execute();
                    $monstre = $newMonstre->fetch(PDO::FETCH_ASSOC);

                    // AJOUTE ALEATOIRE SUR NIVEAU ET PUISSANCE MONSTRE
                    $randLvl = rand(1, 5);

                    // GENERER UN OBJET SALLECOMBAT POUR GERER LE COMBAT ENTRE LE JOUEUR ET LE MONSTRE SELECTIONNE
                    $salleCombat = new SalleCombat('Combat', 'un tres dangereux monstre va apparaitre', new Monstre($monstre['Nom'], $monstre['PV'], $monstre['PA'], $monstre['PD'], $randLvl, $monstre['Exp_donne'], $monstre['Gold_donne']));

                    // $salleCombat->afficherInformations();

                    // RETOURNE SALLECOMBAT
                    return $salleCombat;
                } catch (PDOException $e) {
                    // SI ERREUR
                    echo "Erreur lors de la récupération du monstre: " . $e->getMessage();
                    return NULL;
                }

                // 25% SALLE MARCHAND
            case $rand >= 50 && $rand < 75:
                try {
                    // CHOISIR DEUX ARMES ALEATOIRES DE LA BASE DE DONNEES PROPOSEES PAR LE MARCHAND
                    $newMarchand = $this->bdd->prepare("SELECT * FROM Marchand ORDER BY RAND() LIMIT 2");
                    $newMarchand->execute();

                    $marchands = $newMarchand->fetchAll(PDO::FETCH_ASSOC);

                    // BOUCLE SUR LES DEUX ARMES SELECTIONNEES DE MANIERE ALEATOIRE
                    foreach ($marchands as $key => $marchand) {
                        $ArmeId = $marchand['Id_arme'];

                        // SELECTIONNE LES DETAILS DE CHAQUE ARME
                        $newArme = $this->bdd->prepare("SELECT * FROM armes WHERE Id_arme = $ArmeId");
                        $newArme->execute();
                        $NouvelleArme = $newArme->fetch(PDO::FETCH_ASSOC);

                        // CREER UN OBJET ARME POUR CHAQUE ARME SELECTIONEE DE MANIERE ALEATOIRE
                        $MarchandArmes[$key] = new Arme($NouvelleArme['Nom_arme'], "Arme", $NouvelleArme['Bonus'], $NouvelleArme['Malus'], $NouvelleArme['Type'], $NouvelleArme['Degat'], "test", $NouvelleArme['Niv_requis'], $NouvelleArme['Prix']);
                    }

                    // CREER UN OBJET SALLEMARCHAND AVEC DES DEUX OBJETS ARME SELECTIONNES
                    $salleMarchand = new SalleMarchand('Marchand', 'Un marchand vous propose des objets', $MarchandArmes[0], $MarchandArmes[1]);

                    // RETOURNE SALLEMARCHAND
                    return $salleMarchand;
                } catch (PDOException $e) {
                    // SI ERREUR
                    echo "Erreur lors de la récupération du marchand: " . $e->getMessage();
                    return false;
                }

                // 15% SALLE ENIGME
            case $rand >= 75 && $rand < 90:
                echo "salle énigme";
                // CHOISIR UNE ENIGME ALEATOIRE A PARTIR DE LA BASE DE DONNEE
                try {
                    $newEnigme = $this->bdd->prepare("SELECT * FROM Enigme ORDER BY RAND() LIMIT 1");
                    $newEnigme->execute();
                    $enigme = $newEnigme->fetch(PDO::FETCH_ASSOC);

                    // CREER UN OBJET SALLEENIGME AVEC REPONSES BONNE REPONSE ET ENNONCE
                    $salleEnigme = new SalleEnigme('Énigme', 'Une énigme vous attend', $enigme['Enigme'], $enigme['Rep1'], $enigme['Rep2'], $enigme['Rep3'], $enigme['Bonne_rep']);

                    // RETOURNE SALLEENIGME
                    return $salleEnigme;
                } catch (PDOException $e) {
                    // SI ERREUR
                    echo "Erreur lors de la récupération de l'énigme: " . $e->getMessage();
                    return false;
                }

                // 5% SALLE BOSS
            case $rand >= 90 && $rand < 95:
                try {
                    // CHOISIR UN BOSS ALEATOIRE DE LA BASE DE DONNEE DANS LA TABLE MONSTRE PARMI LES MONSTRES AVEC TYPE BOSS
                    $newBoss = $this->bdd->prepare("SELECT * FROM Monstre WHERE Type = 'boss' ORDER BY RAND() LIMIT 1");
                    $newBoss->execute();
                    $Boss = $newBoss->fetch(PDO::FETCH_ASSOC);

                    // AJOUTE ALEATOIRE SUR NIVEAU ET PUISSANCE BOSS
                    $randLvl = rand(1, 5);

                    // CREER SALLEBOSS COMME UN OBJET SALLECOMBAT AVEC UN MONSTRE TYPE BOSS
                    $salleBoss = new SalleCombat('Combat', 'Un très dangereux Boss va apparaitre', new Monstre($Boss['Nom'], $Boss['PV'], $Boss['PA'], $Boss['PD'], $randLvl, $Boss['Exp_donne'], $Boss['Gold_donne']));

                    // RETOURNE SALLEBOSS
                    return $salleBoss;
                } catch (PDOException $e) {
                    // SI ERREUR
                    echo "Erreur lors de la récupération du Boss : " . $e->getMessage();
                    return NULL;
                }

                // 5% SALLE VIDE RIEN DE SPECIAL LA PROGRESSION CONTINUE
            case $rand >= 95 && $rand <= 100:
                echo "Vous êtes tombé sur une salle vide !";
                $salleVide = new SalleVide('Vide', 'Une salle vide');
                return $salleVide;

                // SI ERREUR
            default:
                echo "erreur dans la sélection de la salle";
                break;
        }
    }

    public function getArmeIDfromName(String $arme) {
        try {
            $requete = $this->bdd->prepare("SELECT Id_arme FROM armes WHERE Nom_arme = ?");
            $requete->execute([$arme]);
            $result = $requete->fetch(PDO::FETCH_ASSOC);
            return $result['Id_arme'];
        } catch (PDOException $e) {
            echo "Erreur de recherche d'arme: " . $e->getMessage();
            return NULL;
        }
    }
    public function getPlayerIDfromName(String $name) {
        try {
            // SELECTIONNE L'ID D'UN PERSONNAGE A PARTIR DE SON NOM
            $requete = $this->bdd->prepare("SELECT id_perso FROM personnages WHERE nom = ?");
            $requete->execute([$name]);
            $result = $requete->fetch(PDO::FETCH_ASSOC);
            // RETOURNE L'ID DU PERSONNAGE
            return $result['id_perso'];
        } catch (PDOException $e) {
            // SI ERREUR
            echo "Erreur de recherche de personnage: " . $e->getMessage();
            return NULL;
        }
    }
    
    public function Sauvegarder(Personnage $joueur) {
        try {
            try {
                // RECUPERE DE L'INVENTAIRE DU JOUEUR
                $requete2 = $this->bdd->prepare("SELECT id_inventaire FROM inventaire WHERE Id_perso = ?");
                $requete2->execute([$this->getPlayerIDfromName($joueur->getName())]);
                $result = $requete2->fetch(PDO::FETCH_ASSOC);
                $id_inventaire = $result['id_inventaire'];
            } catch (PDOException $e) {
                // SI ERREUR
                echo "Erreur de recherche d'inventaire: " . $e->getMessage();
                return false;
            }

            // MISE A JOUR DES DONNEES DU PERSONNAGE ET SON INVENTAIRE DANS LA BASE DE DONNEES
            $requete = $this->bdd->prepare("UPDATE personnages SET PV = ?, PA = ?, PD = ?, EXP = ?, Niveau = ?, id_inventaire = ? WHERE nom = ?");
            $requete->execute([$joueur->getPV(), $joueur->getPA(), $joueur->getPD(), $joueur->getCurrentEXP(), $joueur->getLevel(), $id_inventaire, $joueur->getName()]);
            return true;
        } catch (PDOException $e) {
            // SI ERREUR
            echo "Erreur de sauvegarde de personnage: " . $e->getMessage();
            return false;
        }
    }
}
