<?php
// déclaration d'une classe Instrument
class Instrument
{
	// attributs en relation avec la base de données
	public $id;
	public $nom;
	public $description;
	public $prix;
	public $id_type;
	public $id_auteur;
	public $verification;

	// constructeur pour normaliser les valeurs
	function __construct()
	{
		if (is_null($this->id))
			$this->id = 0;
		if (is_null($this->nom))
			$this->nom = '';
		if (is_null($this->description))
			$this->description = '';
		if (is_null($this->prix))
			$this->prix = 0;
		if (is_null($this->id_type))
			$this->id_type = 0;
		if (is_null($this->id_auteur))
			$this->id_auteur = 0;
		if (is_null($this->verification))
			$this->verification = false;
	}

	// modifier les attributs
	function modifie($nom, $description, $prix = 0, $id_type = 0, $id_auteur = 0)
	{
		$this->nom = $nom;
		$this->description = $description;
		$this->prix = $prix;
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
		if (isset($_POST['description'])) {
			$this->description = $_POST['description'];
		}
		if (isset($_POST['prix'])) {
			$this->prix = floatval($_POST['prix']);
		}
		if (isset($_POST['id_type'])) {
			$this->id_type = intval($_POST['id_type']);
		}
		if (isset($_POST['id_auteur'])) {
			$this->id_auteur = intval($_POST['id_auteur']);
		}
		if (isset($_POST['verification'])) {
			$this->verification = intval($_POST['verification']) ? true : false;
		}
	}

	// récupérer un seul instrument
	static function readOne($id)
	{
		$sql = 'SELECT i.*, u.pseudo, t.nom AS type_nom FROM instruments i JOIN utilisateurs u ON i.id_auteur = u.id JOIN types t ON i.id_type = t.id WHERE i.id = :valeur;';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':valeur', $id, PDO::PARAM_INT);
		$query->execute();
		$objet = $query->fetchObject('Instrument');
		return $objet;
	}

	// récupérer tous les instruments
	static function readAll(array $filters = [])
	{
		$sql = "
		SELECT i.*, u.pseudo, t.nom AS type_nom
		FROM instruments i
		JOIN utilisateurs u ON i.id_auteur = u.id
		JOIN types t ON i.id_type = t.id
		WHERE 1=1
	";

		$params = [];

		// Filter by type
		if (!empty($filters['id_type'])) {
			$sql .= " AND i.id_type = :id_type";
			$params['id_type'] = $filters['id_type'];
		}

		// Filter by author
		if (!empty($filters['id_auteur'])) {
			$sql .= " AND i.id_auteur = :id_auteur";
			$params['id_auteur'] = $filters['id_auteur'];
		}

		// Filter by name
		if (!empty($filters['nom'])) {
			$sql .= " AND i.nom LIKE :nom";
			$params['nom'] = '%' . $filters['nom'] . '%';
		}

		if (isset($filters['verif']) && $filters['verif'] !== '') {
			$sql .= " AND i.verification = :verif";
			$params['verif'] = (int) $filters['verif'];
		}

		// Ordering by price: accept 'prix_asc' or 'prix_desc'
		if (!empty($filters['order'])) {
			if ($filters['order'] === 'prix_asc') {
				$sql .= " ORDER BY i.prix ASC";
			} elseif ($filters['order'] === 'prix_desc') {
				$sql .= " ORDER BY i.prix DESC";
			}
		}

		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->execute($params);

		return $query->fetchAll(PDO::FETCH_CLASS, 'Instrument');
	}

	// read only verified / published instruments
	static function readAllPublished(array $filters = [])
	{
		$sql = "
		SELECT i.*, u.pseudo, t.nom AS type_nom
		FROM instruments i
		JOIN utilisateurs u ON i.id_auteur = u.id
		JOIN types t ON i.id_type = t.id
		WHERE 1=1
		AND i.verification = 1
	";

		$params = [];

		// si les filtres sont remplis, on rajoute à la fin de la requête SQL avec le filtre correspondant
// permet de combiner les filtres

		if (!empty($filters['id_type'])) {
			$sql .= " AND i.id_type = :id_type";
			$params['id_type'] = $filters['id_type'];
		}

		if (!empty($filters['id_auteur'])) {
			$sql .= " AND i.id_auteur = :id_auteur";
			$params['id_auteur'] = $filters['id_auteur'];
		}

		if (!empty($filters['nom'])) {
			$sql .= " AND i.nom LIKE :nom";
			$params['nom'] = '%' . $filters['nom'] . '%';
		}

		if (!empty($filters['order'])) {
			if ($filters['order'] === 'prix_asc') {
				$sql .= " ORDER BY i.prix ASC";
			} elseif ($filters['order'] === 'prix_desc') {
				$sql .= " ORDER BY i.prix DESC";
			}
		}

		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->execute($params);

		return $query->fetchAll(PDO::FETCH_CLASS, 'Instrument');
	}


	// créer
	function create()
	{
		$sql = 'INSERT INTO instruments (nom, description, prix, id_type, id_auteur, verification) VALUES (:nom, :description, :prix, :id_type, :id_auteur, :verification);';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
		$query->bindValue(':description', $this->description, PDO::PARAM_STR);
		$query->bindValue(':prix', $this->prix, PDO::PARAM_STR);
		$query->bindValue(':id_type', $this->id_type, PDO::PARAM_INT);
		$query->bindValue(':id_auteur', $this->id_auteur, PDO::PARAM_INT);
		$query->bindValue(':verification', $this->verification ? 1 : 0, PDO::PARAM_INT);
		$query->execute();
		$this->id = $pdo->lastInsertId();
	}

	// mettre à jour
	function update()
	{
		$sql = 'UPDATE instruments SET nom = :nom, description = :description, prix = :prix, id_type = :id_type, id_auteur = :id_auteur, verification = :verification WHERE id = :id;';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $this->id, PDO::PARAM_INT);
		$query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
		$query->bindValue(':description', $this->description, PDO::PARAM_STR);
		$query->bindValue(':prix', $this->prix, PDO::PARAM_STR);
		$query->bindValue(':id_type', $this->id_type, PDO::PARAM_INT);
		$query->bindValue(':id_auteur', $this->id_auteur, PDO::PARAM_INT);
		$query->bindValue(':verification', $this->verification ? 1 : 0, PDO::PARAM_INT);
		$query->execute();
	}

	// supprimer
	static function delete($id)
	{
		$sql = 'DELETE FROM instruments WHERE id = :id;';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();
	}
}

