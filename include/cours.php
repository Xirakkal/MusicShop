<?php
// déclaration d'une classe Cours
class Cours
{
    // attributs en relation avec la base de données
    public $id;
    public $nom;
    public $prix;
    public $difficulte;
    public $description;
    public $id_type;
    public $id_auteur;

    // constructeur pour normaliser les valeurs
    function __construct()
    {
        if (is_null($this->id))
            $this->id = 0;
        if (is_null($this->nom))
            $this->nom = '';
        if (is_null($this->prix))
            $this->prix = 0;
        if (is_null($this->difficulte))
            $this->difficulte = '';
        if (is_null($this->description))
            $this->description = '';
        if (is_null($this->id_type))
            $this->id_type = 0;
        if (is_null($this->id_auteur))
            $this->id_auteur = 0;
    }

    // modifier les attributs
    function modifie($nom, $prix = 0, $difficulte = '', $description = '', $id_type = 0, $id_auteur = 0)
    {
        $this->nom = $nom;
        $this->prix = $prix;
        $this->difficulte = $difficulte;
        $this->description = $description;
        $this->id_type = $id_type;
        $this->id_auteur = $id_auteur;
    }

    // charger depuis $_POST
    function chargePOST()
    {
        if (isset($_POST['id'])) {
            $this->id = intval($_POST['id']);
        }
        if (isset($_POST['nom'])) {
            $this->nom = strip_tags($_POST['nom']);
        }
        if (isset($_POST['prix'])) {
            $this->prix = floatval($_POST['prix']);
        }
        if (isset($_POST['difficulte'])) {
            $this->difficulte = strip_tags($_POST['difficulte']);
        }
        if (isset($_POST['description'])) {
            $this->description = $_POST['description'];
        }
        if (isset($_POST['id_type'])) {
            $this->id_type = intval($_POST['id_type']);
        }
        if (isset($_POST['id_auteur'])) {
            $this->id_auteur = intval($_POST['id_auteur']);
        }
    }

    // récupérer un seul cours
    static function readOne($id)
    {
        $sql = 'SELECT c.*, u.pseudo, t.nom AS type_nom FROM cours c JOIN utilisateurs u ON c.id_auteur = u.id JOIN types t ON c.id_type = t.id WHERE c.id = :valeur;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':valeur', $id, PDO::PARAM_INT);
        $query->execute();
        $objet = $query->fetchObject('Cours');
        return $objet;
    }

    // récupérer tous les cours
    static function readAll(array $filters = [])
    {
        $sql = "
        SELECT c.*, u.pseudo, t.nom AS type_nom
        FROM cours c
        JOIN utilisateurs u ON c.id_auteur = u.id
        JOIN types t ON c.id_type = t.id
        WHERE 1=1
    ";

        $params = [];

// si les filtres sont remplis, on rajoute à la fin de la requête SQL avec le filtre correspondant
// permet de combiner les filtres

        if (!empty($filters['id_type'])) {
            $sql .= " AND c.id_type = :id_type";
            $params['id_type'] = $filters['id_type'];
        }

        if (!empty($filters['id_auteur'])) {
            $sql .= " AND c.id_auteur = :id_auteur";
            $params['id_auteur'] = $filters['id_auteur'];
        }

        if (!empty($filters['nom'])) {
            $sql .= " AND c.nom LIKE :nom";
            $params['nom'] = '%' . $filters['nom'] . '%';
        }

        if (!empty($filters['difficulte'])) {
            $sql .= " AND c.difficulte = :difficulte";
            $params['difficulte'] = $filters['difficulte'];
        }

        if (!empty($filters['order'])) {
            if ($filters['order'] === 'prix_asc') {
                $sql .= " ORDER BY c.prix ASC";
            } elseif ($filters['order'] === 'prix_desc') {
                $sql .= " ORDER BY c.prix DESC";
            }
        }

        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute($params);

        return $query->fetchAll(PDO::FETCH_CLASS, 'Cours');
    }

    // créer
    function create()
    {
        $sql = 'INSERT INTO cours (nom, prix, difficulte, description, id_type, id_auteur) VALUES (:nom, :prix, :difficulte, :description, :id_type, :id_auteur);';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $query->bindValue(':prix', $this->prix, PDO::PARAM_STR);
        $query->bindValue(':difficulte', $this->difficulte, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':id_type', $this->id_type, PDO::PARAM_INT);
        $query->bindValue(':id_auteur', $this->id_auteur, PDO::PARAM_INT);
        $query->execute();
        $this->id = $pdo->lastInsertId();
    }

    // mettre à jour
    function update()
    {
        $sql = 'UPDATE cours SET nom = :nom, prix = :prix, difficulte = :difficulte, description = :description, id_type = :id_type, id_auteur = :id_auteur WHERE id = :id;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $query->bindValue(':prix', $this->prix, PDO::PARAM_STR);
        $query->bindValue(':difficulte', $this->difficulte, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':id_type', $this->id_type, PDO::PARAM_INT);
        $query->bindValue(':id_auteur', $this->id_auteur, PDO::PARAM_INT);
        $query->execute();
    }

    // supprimer
    static function delete($id)
    {
        $sql = 'DELETE FROM cours WHERE id = :id;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }
}
