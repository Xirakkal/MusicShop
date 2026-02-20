<?php
// déclaration d'une classe Type
class Type
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

    // récupérer un seul type
    static function readOne($id)
    {
        $sql = 'SELECT * FROM types WHERE id = :valeur';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':valeur', $id, PDO::PARAM_INT);
        $query->execute();
        $objet = $query->fetchObject('Type');
        return $objet;
    }

    // récupérer tous les types
    static function readAll()
    {
        $sql = 'SELECT * FROM types';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute();
        $tableau = $query->fetchAll(PDO::FETCH_CLASS, 'Type');
        return $tableau;
    }

    // créer
    function create()
    {
        $sql = 'INSERT INTO types (nom) VALUES (:nom);';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $query->execute();
        $this->id = $pdo->lastInsertId();
    }

    // mettre à jour
    function update()
    {
        $sql = 'UPDATE types SET nom = :nom WHERE id = :id;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $query->execute();
    }

    // supprimer
    static function delete($id)
    {
        $sql = 'DELETE FROM types WHERE id = :id;';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }
}