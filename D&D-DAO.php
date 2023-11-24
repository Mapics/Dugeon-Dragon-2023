<?php
class DD_DAO
{
    protected $bdd;

    public function __construct($bdd)
    {
        $this->bdd = $bdd;
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
                        
                        $MarchandArmes[$key] = new Arme($NouvelleArme['Nom_arme'], "Arme" , $NouvelleArme['Bonus'], $NouvelleArme['Malus'], $NouvelleArme['Type'], $NouvelleArme['Degat'], "", $NouvelleArme['Niv_requis']);
                    }
                    
                    $salleMarchand = new SalleMarchand('Marchand', 'Un marchand vous propose des objets', $MarchandArmes[0], $MarchandArmes[1]);

                    return $salleMarchand;
                } catch (PDOException $e) {
                    echo "Erreur lors de la récupération du marchand: " . $e->getMessage();
                    return false;
                }

            case $rand >= 75 && $rand < 90:
                echo "salle énigme";
                $salleEnigme = new SalleEnigme('Énigme', 'une énigme vous attend', 'Quelle est la couleur du cheval blanc de Henri IV ?');
                return $salleEnigme;
            case $rand >= 90 && $rand < 95:
                echo "salle boss";
                $salleBoss = new SalleBoss('Boss', 'un boss vous attend', new Monstre('Boss', 10));
                return $salleBoss;
            case $rand >= 95 && $rand <= 100:
                echo "salle vide";
                return null;
            default:
                echo "erreur dans la sélection de la salle";
                break;
        }
    }
}
