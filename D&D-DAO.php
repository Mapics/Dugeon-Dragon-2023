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
            return true;
        } catch (PDOException $e) {
            echo "Erreur d'ajout de personnage: " . $e->getMessage();
            return false;
        }
    }
    public function ajouterInventaireBDD(Inventaire $inventaire, Personnage $joueur) {
        try {
            $requete = $this->bdd->prepare("INSERT INTO inventaire (Id_arme, Id_perso) VALUES (?, ?)");
            $requete->execute([$inventaire->getArme()->getId(), $joueur->getId()]);
        
            return true;
        } catch (PDOException $e) {
            echo "Erreur d'ajout d'inventaire: " . $e->getMessage();
            return false;
        }

    }

    public function verifJoueurExistant($player_name, $PV, $PA, $PD, $EXP, $Niveau) {
        try {
            $requete = $this->bdd->prepare("SELECT * FROM personnages WHERE nom = ?");
            $requete->execute([$player_name]);
            $result = $requete->fetchAll(PDO::FETCH_ASSOC);
            $player = new Joueur($result['nom'], array());
            $player->setPlayerSave($PV, $PA, $PD, $EXP, $Niveau);


            if (count($result) > 0) {
                return $player;
            } else {
                return NULL;
            }
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
        $rand = rand(50, 75);
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
                return null;

                // SI ERREUR
            default:
                echo "erreur dans la sélection de la salle";
                break;
        }
    }

    public function Sauvegarder(Personnage $joueur) {
        try {
            $requete = $this->bdd->prepare("UPDATE personnages SET PV = ?, PA = ?, PD = ?, EXP = ?, Niveau = ? WHERE nom = ?");
            $requete->execute([$joueur->getPV(), $joueur->getPA(), $joueur->getPD(), $joueur->getCurrentEXP(), $joueur->getLevel(), $joueur->getName()]);
            return true;
        } catch (PDOException $e) {
            echo "Erreur de sauvegarde de personnage: " . $e->getMessage();
            return false;
        }
    }
}
