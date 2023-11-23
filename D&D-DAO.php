<?php
class DD_DAO {
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
        switch ($rand) {
            case 0 < 50:
                // TODO requete vers la bdd pour 'new' une vrais salle a return
                echo "salle combat";
                $salleCombat = new SalleCombat('Combat', 'un tres dangereux monstre va apparaitre', "new Monstre('Pikachu', 1)");
                $salleCombat->afficherInformations();
                return $salleCombat;
            case 51 < 75:
                echo "salle marchand";
                $salleMarchand = new SalleMarchand('Marchand', 'un marchand vous propose des objets', ['Objet', 'Un autre']);
                $salleMarchand->afficherInformations();
                return $salleMarchand;
            case 76 < 90:
                echo "salle enigme";
                $salleEnigme = new SalleEnigme('Enigme', 'une enigme vous attend', 'Quel est la couleur du cheval blanc d henri IV ?');
                $salleEnigme->afficherInformations();
                return $salleEnigme;
            case 91 < 95;
                echo "salle boss";
                $salleBoss = new SalleBoss('Boss', 'un boss vous attend', "new Monstre('Boss', 10)");
                $salleBoss->afficherInformations();
                return $salleBoss;
            case 96 < 100:
                echo "salle vide";
            default:
                echo "erreur dans la sélection de la salle";
                break;
        }
    }
}
