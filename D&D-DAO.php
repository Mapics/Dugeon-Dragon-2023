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
        $rand = rand(0, 100);
        switch (true) {
            case $rand < 50:
                echo "salle combat";
                try {
                    $newMonstre = $this->bdd->prepare("SELECT * FROM Monstre WHERE Type = 'normal' ORDER BY RAND() LIMIT 1");
                    $newMonstre->execute();

                    $monstre = $newMonstre->fetch(PDO::FETCH_ASSOC);

                    $randLvl = rand(1, 5);

                    $salleCombat = new SalleCombat('Combat', 'un tres dangereux monstre va apparaitre', "new Monstre('" . $monstre['Nom'] . "', " . $randLvl . ")");
                    
                    $salleCombat->afficherInformations();

                    return true;
                } catch (PDOException $e) {
                    echo "Erreur lors de la récupération du monstre: " . $e->getMessage();
                    return false;
                }
                // $salleCombat = new SalleCombat('Combat', 'un tres dangereux monstre va apparaitre', "new Monstre('Pikachu', 1)");
                // $salleCombat->afficherInformations();
                
            case 51 < 75:
                $salleCombat = new SalleCombat('Combat', 'un très dangereux monstre va apparaître', new Monstre('Pikachu', 1));
                $salleCombat->afficherInformations();
                return $salleCombat;
            case $rand >= 50 && $rand < 75:
                echo "salle marchand";
                $salleMarchand = new SalleMarchand('Marchand', 'un marchand vous propose des objets', ['Objet', 'Un autre']);
                $salleMarchand->afficherInformations();
                return $salleMarchand;
            case $rand >= 75 && $rand < 90:
                echo "salle énigme";
                $salleEnigme = new SalleEnigme('Énigme', 'une énigme vous attend', 'Quelle est la couleur du cheval blanc de Henri IV ?');
                $salleEnigme->afficherInformations();
                return $salleEnigme;
            case $rand >= 90 && $rand < 95:
                echo "salle boss";
                $salleBoss = new SalleBoss('Boss', 'un boss vous attend', new Monstre('Boss', 10));
                $salleBoss->afficherInformations();
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
