<?php
// déclaration d'une classe Style
class Style
{
    // attributs en relation avec la base de données
    public $id;
    public $nom;

    // constructeur pour normaliser les valeurs
    function __construct()
    {
        if (is_null($this->id)) $this->id = 0;
        if (is_null($this->nom)) $this->nom = '';
    }

    // modifier les attributs
    function modifie($nom)
    {
        $this->nom = $nom;
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
    }

    // récupérer un seul style
    static function readOne($id)
    {
        $sql = 'SELECT * FROM styles WHERE id = :valeur';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':valeur', $id, PDO::PARAM_INT);
        $query->execute();
        $objet = $query->fetchObject('Style');
        return $objet;
    }

    // récupérer tous les styles
    static function readAll()
    {
        $sql = 'SELECT * FROM styles';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute();
        $tableau = $query->fetchAll(PDO::FETCH_CLASS, 'Style');
        return $tableau;
    }

    // créer
    function create()
    {
        $sql = 'INSERT INTO styles (nom) VALUES (:nom);';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $query->execute();
        $this->id = $pdo->lastInsertId();
    }

    // mettre à jour
    function update()
    {
        $sql = 'UPDATE styles SET nom = :nom WHERE id = :id;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $query->execute();
    }

    // supprimer
    static function delete($id)
    {
        $sql = 'DELETE FROM styles WHERE id = :id;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }
}
