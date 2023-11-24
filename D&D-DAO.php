<?php
class DD_DAO
{
    protected $bdd;

    public function __construct($bdd)
    {
        $this->bdd = $bdd;
    }

    public function ajouterJoueurBDD(Joueur $joueur) {
        try {
            $requete = $this->bdd->prepare("INSERT INTO personnages (nom, PV, PA, PD, EXP, Niveau) VALUES (?, ?, ?, ?, ?, ?)");
            $requete->execute([$joueur->getName(), $joueur->getPV(), $joueur->getPA(), $joueur->getPD(), $joueur->getCurrentEXP(), $joueur->getLevel()]);
            return true;
        } catch (PDOException $e) {
            echo "Erreur d'ajout de personnage: " . $e->getMessage();
            return false;
        }
    }

    public function verifJoueurExistant($player_name) {
        try {
            $requete = $this->bdd->prepare("SELECT * FROM personnages WHERE nom = ?");
            $requete->execute([$player_name]);
            $result = $requete->fetchAll(PDO::FETCH_ASSOC);
            $joueur = new Joueur($result[0]['nom'], $result[0]['PV'], $result[0]['PA'], $result[0]['PD'], $result[0]['EXP'], $result[0]['Niveau']);
            if(count($result) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Erreur de recherche de personnage: " . $e->getMessage();
            return false;
            
        }
    }

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

    public function salleAleatoire()
    {
        $rand = rand(50, 75);
        switch (true) {
            case $rand < 50:
                try {
                    $newMonstre = $this->bdd->prepare("SELECT * FROM Monstre WHERE Type = 'normal' ORDER BY RAND() LIMIT 1");
                    $newMonstre->execute();

                    $monstre = $newMonstre->fetch(PDO::FETCH_ASSOC);

                    $randLvl = rand(1, 5);

                    $salleCombat = new SalleCombat('Combat', 'un tres dangereux monstre va apparaitre', new Monstre($monstre['Nom'], $monstre['PV'], $monstre['PA'], $monstre['PD'], $randLvl, $monstre['Exp_donne'], $monstre['Gold_donne']));

                    // $salleCombat->afficherInformations();

                    return $salleCombat;
                } catch (PDOException $e) {
                    echo "Erreur lors de la récupération du monstre: " . $e->getMessage();
                    return NULL;
                }

            case $rand >= 50 && $rand < 75:
                try {
                    $newMarchand = $this->bdd->prepare("SELECT * FROM Marchand ORDER BY RAND() LIMIT 2");
                    $newMarchand->execute();

                    $marchands = $newMarchand->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($marchands as $key => $marchand) {
                        $ArmeId = $marchand['Id_arme'];
                        $newArme = $this->bdd->prepare("SELECT * FROM armes WHERE Id_arme = $ArmeId");
                        $newArme->execute();

                        

                        $NouvelleArme = $newArme->fetch(PDO::FETCH_ASSOC);
                        
                        $MarchandArmes[$key] = new Arme($NouvelleArme['Nom_arme'], "Arme" , $NouvelleArme['Bonus'], $NouvelleArme['Malus'], $NouvelleArme['Type'], $NouvelleArme['Degat'], "test", $NouvelleArme['Niv_requis'], $NouvelleArme['Prix']); 
                    }
                    
                    $salleMarchand = new SalleMarchand('Marchand', 'Un marchand vous propose des objets', $MarchandArmes[0], $MarchandArmes[1]);

                    return $salleMarchand;
                } catch (PDOException $e) {
                    echo "Erreur lors de la récupération du marchand: " . $e->getMessage();
                    return false;
                }

            case $rand >= 75 && $rand < 90:
                echo "salle énigme";

                try {
                    $newEnigme = $this->bdd->prepare("SELECT * FROM Enigme ORDER BY RAND() LIMIT 1");
                    $newEnigme->execute();

                    $enigme = $newEnigme->fetch(PDO::FETCH_ASSOC);

                    $salleEnigme = new SalleEnigme('Énigme', 'Une énigme vous attend', $enigme['Enigme'], $enigme['Rep1'], $enigme['Rep2'], $enigme['Rep3'], $enigme['Bonne_rep']);

                    return $salleEnigme;
                } catch (PDOException $e) {
                    echo "Erreur lors de la récupération de l'énigme: " . $e->getMessage();
                    return false;
                }
                // $salleEnigme = new SalleEnigme('Énigme', 'une énigme vous attend', 'Quelle est la couleur du cheval blanc de Henri IV ?');
                // return $salleEnigme;
            case $rand >= 90 && $rand < 95:
                try {
                    $newBoss = $this->bdd->prepare("SELECT * FROM Monstre WHERE Type = 'boss' ORDER BY RAND() LIMIT 1");
                    $newBoss->execute();

                    $Boss = $newBoss->fetch(PDO::FETCH_ASSOC);

                    $randLvl = rand(1, 5);

                    $salleBoss = new SalleCombat('Combat', 'Un très dangereux Boss va apparaitre', new Monstre($Boss['Nom'], $Boss['PV'], $Boss['PA'], $Boss['PD'], $randLvl, $Boss['Exp_donne'], $Boss['Gold_donne']));

                    // $salleCombat->afficherInformations();

                    return $salleBoss;
                } catch (PDOException $e) {
                    echo "Erreur lors de la récupération du Boss : " . $e->getMessage();
                    return NULL;
                }
            case $rand >= 95 && $rand <= 100:
                echo "Vous êtes tombé sur une salle vide !";
                return null;
            default:
                echo "erreur dans la sélection de la salle";
                break;
        }
    }
}
