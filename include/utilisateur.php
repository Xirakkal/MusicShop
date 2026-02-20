<?php
require_once 'connexion.php';

class Utilisateur
{
    public $id;
    public $email;
    public $mdp;
    public $pseudo;
    public $role;
    public $date_creation;

    // exemple de constructeur qui corrige les données récupérée de la BDD
    function __construct()
    {
        $this->id = intval($this->id);
        // ensure role is a string and provide a default
        if (!isset($this->role) || $this->role === '') {
            $this->role = 'visiteur';
        } else {
            $this->role = strval($this->role);
        }
    }

    function modifie($email, $mdp, $pseudo, $role = 'user')
    {
        $this->email = $email;
        $this->mdp = $mdp;
        $this->pseudo = $pseudo;
        $this->role = $role;
        $this->date_creation = '';
    }

    function chargePOST()
    {
        if (isset($_POST['id'])) {
            $this->id = intval($_POST['id']);
        }
        if (isset($_POST['email'])) {
            $this->email = strip_tags($_POST['email']);
        }
        if (isset($_POST['mdp'])) {
            $this->mdp = strip_tags($_POST['mdp']);
        }
        if (isset($_POST['pseudo'])) {
            $this->pseudo = strip_tags($_POST['pseudo']);
        }
        if (isset($_POST['role'])) {
            $this->role = strip_tags($_POST['role']);
        }
    }

    function afficheForm()
    {
        // si id = 0 alors c'est une création, sinon c'est une modification
        if ($this->id == 0)
            $action = 'create';
        else
            $action = 'update';
        echo '<form action="controleur.php?page=utilisateur&action=' . $action . '" method="post">';
        echo '<p><input type="text" name="email" value="' . htmlspecialchars($this->email) . '"></p>';
        echo '<p><input type="text" name="mdp" value="' . htmlspecialchars($this->mdp) . '"></p>';
        echo '<p><input type="text" name="pseudo" value="' . htmlspecialchars($this->pseudo) . '"></p>';
        echo '<p><input type="text" name="role" value="' . htmlspecialchars($this->role) . '"></p>';
        if ($this->id == 0)
            $button = 'Ajouter';
        else
            $button = 'Modifier';
        echo '<p><input type="submit" value="' . $button . '"></p>';
        echo '</form>';
    }

    // cherche un utilisateur avec les identifiants fournis
    function checkUser()
    {
        $sql = 'select * from utilisateurs where email = :email and mdp = :mdp';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':mdp', $this->mdp, PDO::PARAM_STR);
        $query->execute();

        // récupère la ligne sous forme d'objet et le renvoie
        // si le résultat contient une ligne alors l'utilisateur est trouvé
        // sinon le résultat est vide et l'utilisateur n'existe pas
        $objet = $query->fetchObject('Utilisateur');
        return $objet;
    }

    // cherche un utilisateur avec les identifiants fournis
    function checkEmail()
    {
        $sql = 'select * from utilisateurs where email = :email';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->execute();

        // récupère la ligne sous forme d'objet et le renvoie
        // si le résultat contient une ligne alors l'email existe
        // sinon le résultat est vide et l'email n'existe pas
        $objet = $query->fetchObject('Utilisateur');
        return $objet;
    }

    static function readOne($id)
    {
        // définit la requête SQL avec un paramètre :valeur
        $sql = 'select * from utilisateurs where id = :valeur';

        // prépare et exécute la requête
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':valeur', $id, PDO::PARAM_INT);
        $query->execute();

        // récupère la ligne (une seule ici) sous forme d'objet et le renvoie
        $objet = $query->fetchObject('Utilisateur');
        return $objet;
    }

    // récupère un tableau d'objets à partir d'une requête
    // accepte un tableau de filtres :
    // - 'pseudo' => recherche sur le pseudo (LIKE)
    // - 'date_creation' => tri par date_creation 'ASC' ou 'DESC'
    static function readAll(array $filters = [])
    {
        // requête de base
        $sql = 'SELECT * FROM utilisateurs WHERE 1=1';

        $params = [];

        // recherche par pseudo (nom)
        if (!empty($filters['pseudo'])) {
            $sql .= ' AND pseudo LIKE :pseudo';
            $params['pseudo'] = '%' . $filters['pseudo'] . '%';
        }

        // tri par date_creation (valeur attendue: 'ASC' ou 'DESC')
        if (!empty($filters['date_creation'])) {
            $dir = strtoupper($filters['date_creation']) === 'ASC' ? 'ASC' : 'DESC';
            $sql .= ' ORDER BY date_creation ' . $dir;
        }

        // prépare et exécute la requête
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute($params);

        // récupère toutes les lignes sous forme d'un tableau d'objets et le renvoie
        $tableau = $query->fetchAll(PDO::FETCH_CLASS, 'Utilisateur');
        return $tableau;
    }

    function create()
    {
        // définit la requête :nom et :prenom sont les valeurs à insérer
        $sql = 'INSERT INTO utilisateurs (email, mdp, pseudo, role, date_creation)
            VALUES (:email, :mdp, :pseudo, :role, NOW())';

        // prépare et exécute la requête
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':mdp', $this->mdp, PDO::PARAM_STR);
        $query->bindValue(':pseudo', $this->pseudo, PDO::PARAM_STR);
        $query->bindValue(':role', $this->role, PDO::PARAM_STR);
        $query->execute();

        // récupère la clé de l'auteur créé (auto-incrément)
        $this->id = $pdo->lastInsertId();
    }

    function update()
    {
        // définit la requête :nom et :prenom sont les valeurs à modifier
        $sql = 'UPDATE utilisateurs
            SET email = :email, mdp = :mdp, pseudo = :pseudo, role = :role
            WHERE id = :id';

        // prépare et exécute la requête
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':mdp', $this->mdp, PDO::PARAM_STR);
        $query->bindValue(':pseudo', $this->pseudo, PDO::PARAM_STR);
        $query->bindValue(':role', $this->role, PDO::PARAM_STR);
        $query->execute();
    }

    static function delete($id)
    {
        // définit la requête SQL
        $sql = 'DELETE FROM utilisateurs WHERE id = :id;';

        // prépare et exécute la requête
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }
}
