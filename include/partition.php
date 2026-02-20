<?php
// déclaration d'une classe Partition
class Partition
{
    // attributs en relation avec la base de données
    public $id;
    public $nom;
    public $prix;
    public $description;
    public $id_style;
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
        if (is_null($this->description))
            $this->description = '';
        if (is_null($this->id_style))
            $this->id_style = 0;
        if (is_null($this->id_auteur))
            $this->id_auteur = 0;
    }

    // modifier les attributs
    function modifie($nom, $description, $prix = 0, $id_style = 0, $id_auteur = 0)
    {
        $this->nom = $nom;
        $this->description = $description;
        $this->prix = $prix;
        $this->id_style = $id_style;
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
        if (isset($_POST['description'])) {
            $this->description = $_POST['description'];
        }
        if (isset($_POST['prix'])) {
            $this->prix = floatval($_POST['prix']);
        }
        if (isset($_POST['id_style'])) {
            $this->id_style = intval($_POST['id_style']);
        }
        if (isset($_POST['id_auteur'])) {
            $this->id_auteur = intval($_POST['id_auteur']);
        }
    }

    // récupérer une partition
    static function readOne($id)
    {
        $sql = 'SELECT p.*, u.pseudo, s.nom AS style_nom FROM partitions p JOIN utilisateurs u ON p.id_auteur = u.id JOIN styles s ON p.id_style = s.id WHERE p.id = :valeur;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':valeur', $id, PDO::PARAM_INT);
        $query->execute();
        $objet = $query->fetchObject('Partition');
        return $objet;
    }

    // récupérer toutes les partitions
    static function readAll(array $filters = [])
    {
        $sql = "
        SELECT p.*, u.pseudo, s.nom AS style_nom
        FROM partitions p
        JOIN utilisateurs u ON p.id_auteur = u.id
        JOIN styles s ON p.id_style = s.id
        WHERE 1=1
    ";

        $params = [];

// si les filtres sont remplis, on rajoute à la fin de la requête SQL avec le filtre correspondant
// permet de combiner les filtres

        if (!empty($filters['id_style'])) {
            $sql .= " AND p.id_style = :id_style";
            $params['id_style'] = $filters['id_style'];
        }

        if (!empty($filters['id_auteur'])) {
            $sql .= " AND p.id_auteur = :id_auteur";
            $params['id_auteur'] = $filters['id_auteur'];
        }

        if (!empty($filters['nom'])) {
            $sql .= " AND p.nom LIKE :nom";
            $params['nom'] = '%' . $filters['nom'] . '%';
        }

        if (!empty($filters['order'])) {
            if ($filters['order'] === 'prix_asc') {
                $sql .= " ORDER BY p.prix ASC";
            } elseif ($filters['order'] === 'prix_desc') {
                $sql .= " ORDER BY p.prix DESC";
            }
        }

        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute($params);

        return $query->fetchAll(PDO::FETCH_CLASS, 'Partition');
    }

    // créer
    function create()
    {
        $sql = 'INSERT INTO partitions (nom, description, prix, id_style, id_auteur) VALUES (:nom, :description, :prix, :id_style, :id_auteur);';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':prix', $this->prix, PDO::PARAM_STR);
        $query->bindValue(':id_style', $this->id_style, PDO::PARAM_INT);
        $query->bindValue(':id_auteur', $this->id_auteur, PDO::PARAM_INT);
        $query->execute();
        $this->id = $pdo->lastInsertId();
    }

    // mettre à jour
    function update()
    {
        $sql = 'UPDATE partitions SET nom = :nom, description = :description, prix = :prix, id_style = :id_style, id_auteur = :id_auteur WHERE id = :id;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':prix', $this->prix, PDO::PARAM_STR);
        $query->bindValue(':id_style', $this->id_style, PDO::PARAM_INT);
        $query->bindValue(':id_auteur', $this->id_auteur, PDO::PARAM_INT);
        $query->execute();
    }

    // supprimer
    static function delete($id)
    {
        $sql = 'DELETE FROM partitions WHERE id = :id;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }
}
