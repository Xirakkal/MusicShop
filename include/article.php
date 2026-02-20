<?php
// déclaration d'une classe Article
class Article
{
	// attributs en relation avec la base de données
	public $id;
	public $titre;
	public $contenu;
	public $categorie;
	public $auteur;

	// constructeur pour normaliser les valeurs
	function __construct()
	{
		if (is_null($this->id))
			$this->id = 0;
		if (is_null($this->titre))
			$this->titre = '';
		if (is_null($this->contenu))
			$this->contenu = '';
		if (is_null($this->categorie))
			$this->categorie = '';
		if (is_null($this->auteur))
			$this->auteur = '';
	}

	// modifier les attributs
	function modifie($titre, $contenu, $categorie = '', $auteur = '')
	{
		$this->titre = $titre;
		$this->contenu = $contenu;
		$this->categorie = $categorie;
		$this->auteur = $auteur;
	}

	// charger depuis $_POST
	function chargePOST()
	{
		if (isset($_POST['id'])) {
			$this->id = intval($_POST['id']);
		}
		if (isset($_POST['titre'])) {
			$this->titre = strip_tags($_POST['titre']);
		}
		if (isset($_POST['contenu'])) {
			$this->contenu = $_POST['contenu'];
		}
		if (isset($_POST['categorie'])) {
			$this->categorie = strip_tags($_POST['categorie']);
		}
		if (isset($_POST['auteur'])) {
			$this->auteur = strip_tags($_POST['auteur']);
		}
	}

	// récupérer un seul article
	static function readOne($id)
	{
		$sql = 'SELECT a.*, u.pseudo, c.nom FROM articles a JOIN utilisateurs u ON a.id_auteur = u.id JOIN categories c ON a.id_categorie = c.id WHERE a.id = :valeur;';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':valeur', $id, PDO::PARAM_INT);
		$query->execute();
		$objet = $query->fetchObject('Article');
		return $objet;
	}

	// récupérer tous les articles

	/*
		static function readAll()
		{
			$sql = 'select * from articles';
			$pdo = connexion();
			$query = $pdo->prepare($sql);
			$query->execute();
			$tableau = $query->fetchAll(PDO::FETCH_CLASS, 'Article');
			return $tableau;
		}
	*/

	static function readAll(array $filters = [])
	{
		$sql = "
        SELECT a.*, u.pseudo, c.nom
        FROM articles a
        JOIN utilisateurs u ON a.id_auteur = u.id
        JOIN categories c ON a.id_categorie = c.id
        WHERE 1=1
    ";

		$params = [];

// si les filtres sont remplis, on rajoute à la fin de la requête SQL avec le filtre correspondant
// permet de combiner les filtres

		if (!empty($filters['id_categorie'])) {
			$sql .= " AND a.id_categorie = :id_categorie";
			$params['id_categorie'] = $filters['id_categorie'];
		}

		if (!empty($filters['id_auteur'])) {
			$sql .= " AND a.id_auteur = :id_auteur";
			$params['id_auteur'] = $filters['id_auteur'];
		}

		if (!empty($filters['titre'])) {
			$sql .= " AND a.titre LIKE :titre";
			$params['titre'] = '%' . $filters['titre'] . '%';
		}

		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->execute($params);

		return $query->fetchAll(PDO::FETCH_CLASS, 'Article');
	}



	// créer
	function create()
	{
		$sql = 'INSERT INTO articles (titre, contenu, id_categorie, id_auteur) VALUES (:titre, :contenu, :categorie, :auteur);';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
		$query->bindValue(':contenu', $this->contenu, PDO::PARAM_STR);
		$query->bindValue(':categorie', $this->categorie, PDO::PARAM_STR);
		$query->bindValue(':auteur', $this->auteur, PDO::PARAM_STR);
		$query->execute();
		$this->id = $pdo->lastInsertId();
	}

	// mettre à jour
	function update()
	{
		$sql = 'UPDATE articles SET titre = :titre, contenu = :contenu, id_categorie = :categorie, id_auteur = :auteur WHERE id = :id;';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $this->id, PDO::PARAM_INT);
		$query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
		$query->bindValue(':contenu', $this->contenu, PDO::PARAM_STR);
		$query->bindValue(':categorie', $this->categorie, PDO::PARAM_STR);
		$query->bindValue(':auteur', $this->auteur, PDO::PARAM_STR);
		$query->execute();
	}

	// supprimer
	static function delete($id)
	{
		$sql = 'DELETE FROM articles WHERE id = :id;';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();
	}
}

