<?php
session_start();

include 'include/twig.php';
include 'include/utilisateur.php';
include 'include/article.php';
include 'include/categorie.php';
include 'include/type.php';
include 'include/instrument.php';
include 'include/style.php';
include 'include/partition.php';
include 'include/cours.php';

$twig = init_twig();


$page = isset($_GET['page']) ? $_GET['page'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : 'read';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

switch ($page) {
    case 'user':
        switch ($action) {
            case 'register';
                $view = 'compte/compte_step1.twig';
                $data = ['titre' => 'Création de compte'];
                break;
            case 'check_exist';
                $user = new Utilisateur();
                $user->chargePOST();
                $readUser = $user->checkEmail();
                if ($readUser) {
                    // email déjà utilisé, retour au formulaire d'inscription
                    header('Location: controleur.php?page=user&action=register');
                } else {
                    // email disponible, afficher le formulaire mot de passe
                    $view = 'compte/compte_step2.twig';
                    $data = [
                        'titre' => 'Création de compte',
                        'user_data' => $user
                    ];
                }
                break;
            case 'create':
                $user = new Utilisateur();
                $user->chargePOST();
                $user->create();
                if ($user->id > 0) {
                    // création réussie, saisie des autres champs
                    $view = 'compte/compte_step3.twig';
                    $data = [
                        'titre' => 'Création de compte',
                        'user_data' => $user
                    ];
                } else {
                    // échec de la création, retour au formulaire d'inscription
                    header('Location: controleur.php?page=user&action=register');
                }
                break;
            case 'update':
                // envoi des nouvelles info vers la BDD
                $user = new Utilisateur();
                $user->chargePOST();
                $user->update();
                $_SESSION = [
                    'user' => $user->pseudo,
                    'id' => $user->id,
                    'role' => $user->role
                ];
                header('Location: controleur.php?page=user&action=read&id=' . $user->id . '');
                break;
            case 'login':
                $view = 'compte/compte_connexion.twig';
                $data = ['titre' => 'Connexion'];
                break;
            case 'check_login':
                $user = new Utilisateur();
                $user->chargePOST();
                $readUser = $user->checkUser();
                if ($readUser) {
                    // utilisateur trouvé, on le stocke en session
                    $_SESSION = [
                        'user' => $readUser->pseudo,
                        'id' => $readUser->id,
                        'role' => $readUser->role
                    ];
                    header('Location: index.php');
                } else {
                    // utilisateur non trouvé, retour au formulaire de login
                    header('Location: controleur.php?page=user&action=login');
                }
                break;
            case 'logout':
                session_destroy();
                header('Location: index.php');
                break;
            case 'read':
                if ($id > 0 && $_SESSION['id'] == $id) { // on peut accéder uniquement à son compte
                    $user = Utilisateur::readOne($id);
                    // Lire uniquement les créations (blog, annonces, ...) de cet utilisateur
                    $filters = ['id_auteur' => $user->id];
                    $articles = Article::readAll($filters);
                    $instruments = Instrument::readAll($filters);
                    $partitions = Partition::readAll($filters);
                    $cours = Cours::readAll($filters);
                    $view = 'compte/compte_read.twig';
                    $data = [
                        'user' => $user,
                        'titre' => 'Mon compte',
                        'articles' => $articles,
                        'instruments' => $instruments,
                        'partitions' => $partitions,
                        'cours' => $cours,
                    ];
                } else {
                    header('Location: index.php');
                }
                break;
            case 'delete':
                // on peut supprimer seulement son compte
                if ($id <= 0 || $_SESSION['id'] != $id) {
                    header('Location: index.php');
                    break;
                }

                // si le POST['confirm'] existe et contient la bonne valeur, on supprime
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                    Utilisateur::delete($id);
                    header('Location: controleur.php?page=user&action=logout');
                    exit();
                } else {
                    // sinon on demande confirmation par POST
                    $user = Utilisateur::readOne($id);
                    $view = 'compte/comptes_delete_confirm.twig';
                    $data = [
                        'user' => $user,
                        'titre' => 'Confirmer la suppression du compte'
                    ];
                }
                break;
        }
        break;
    case 'article':
        switch ($action) {
            case 'new':
                // check du rôle
                if ($_SESSION['role'] == 'rédacteur' || $_SESSION['role'] == 'administrateur') {
                    $categories = Categorie::readAll();
                    $view = 'article/article_form.twig';
                    $data = [
                        'titre' => 'Création d\'article',
                        'categories' => $categories
                    ];
                    break;
                } else {
                    header('Location: index.php');
                }
            case 'create':
                // check du rôle
                if ($_SESSION['role'] == 'rédacteur' || $_SESSION['role'] == 'administrateur') {
                    $article = new Article();
                    $article->chargePOST();
                    $article->create();
                    header('Location: controleur.php?page=article&action=read&id=' . $article->id . '');
                    break;
                } else {
                    header('Location: index.php');
                }
            case 'read':
                if ($id > 0) {
                    $article = Article::readOne($id);
                    $view = 'article/article_unique.twig';
                    $data = [
                        'titre' => $article->titre,
                        'article' => $article
                    ];
                } else {
                    // les méthodes ont des conditionnelles pour appliquer les filtres selon si les variables sont remplies ou non
                    $filters = [
                        'id_categorie' => $_POST['categorieFilter'] ?? null,
                        'titre' => $_POST['search'] ?? null,
                    ];
                    $articles = Article::readAll($filters);
                    $categories = Categorie::readAll();
                    $view = 'article/article_cards.twig';
                    // Les éléments "active" existent pour que les filtres se rappellent les options sélectionnées
                    $data = ['titre' => 'Articles', 'articles' => $articles, 'categories' => $categories, 'activeCategorie' => $filters['id_categorie'], 'activeSearch' => $filters['titre']];
                }
                break;
            case 'edit':
                // tous les rédacteurs peuvent édit
                if ($_SESSION['role'] == 'rédacteur' || $_SESSION['role'] == 'administrateur') {
                    $article = Article::readOne($id);
                    $categories = Categorie::readAll();
                    $view = 'article/article_form_edit.twig';
                    $data = [
                        'titre' => 'Modification de l\'article',
                        'article' => $article,
                        'categories' => $categories
                    ];
                    break;
                } else {
                    header('Location: index.php');
                }
            case 'update':
                if ($_SESSION['role'] == 'rédacteur' || $_SESSION['role'] == 'administrateur') {
                    $article = new Article();
                    $article->chargePOST();
                    $article->update();
                    header('Location: controleur.php?page=article&action=read&id=' . $article->id . '');
                    break;
                } else {
                    header('Location: index.php');
                }
            case 'delete':
                $article = Article::readOne($id);
                // seul l'auteur et l'admin peuvent supprimer
                if (!($article && ($_SESSION['id'] == $article->id_auteur || $_SESSION['role'] == 'administrateur'))) {
                    header('Location: controleur.php?page=article&action=read&id=' . $id . '');
                    break;
                }

                // si le POST contient la bonne valeur, on supprime
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                    Article::delete($id);
                    header('Location: controleur.php?page=article&action=read');
                    exit();
                } else {
                    // sinon on demande confirmation par POST
                    $view = 'article/article_delete_confirm.twig';
                    $data = [
                        'article' => $article,
                        'titre' => 'Confirmer la suppression de l\'article'
                    ];
                }
                break;
        }
        break;
    case 'instrument': // annonce pour un instrument de 2nde main
        switch ($action) {
            case 'new':
                // besoin d'être connecté pour créer une annonce
                if (!empty($_SESSION['id'])) {
                    $types = Type::readAll();
                    $view = 'instrument/instrument_form.twig';
                    $data = [
                        'titre' => 'Ajout d\'instrument',
                        'types' => $types
                    ];
                    break;
                } else {
                    header('Location: index.php');
                }
            case 'create':
                if (!empty($_SESSION['id'])) {
                    $instrument = new Instrument();
                    $instrument->chargePOST();
                    $instrument->id_auteur = $_SESSION['id'];
                    $instrument->create();
                    header('Location: controleur.php?page=instrument&action=read&id=' . $instrument->id . '');
                    break;
                } else {
                    header('Location: index.php');
                }
            case 'read':
                if ($id > 0) {
                    $instrument = Instrument::readOne($id);
                    $view = 'instrument/instrument_unique.twig';
                    $data = [
                        'titre' => $instrument->nom,
                        'instrument' => $instrument
                    ];
                } else {
                    $filters = [
                        'id_type' => $_POST['typeFilter'] ?? null,
                        'nom' => $_POST['search'] ?? null,
                        'order' => $_POST['order'] ?? null,
                    ];
                    $instruments = Instrument::readAllPublished($filters); // lire que les annonces validées
                    $types = Type::readAll();
                    $view = 'instrument/instrument_cards.twig';
                    // Les éléments "active" existent pour que les filtres se rappellent les options sélectionnées
                    $data = ['titre' => 'Instruments', 'instruments' => $instruments, 'types' => $types, 'activeType' => $filters['id_type'], 'activeSearch' => $filters['nom'], 'activeOrder' => $filters['order']];
                }
                break;
            case 'edit':
                $instrument = Instrument::readOne($id);
                // on peut modifier et supprimer seulement si on est un admin ou l'auteur de l'annonce
                if ($instrument && (!empty($_SESSION['id']) && ($_SESSION['id'] == $instrument->id_auteur || (($_SESSION['role'] ?? '') == 'administrateur') || (($_SESSION['role'] ?? '') == 'modérateur')))) {
                    $types = Type::readAll();
                    $view = 'instrument/instrument_form_edit.twig';
                    $data = [
                        'titre' => 'Modification de l\'instrument',
                        'instrument' => $instrument,
                        'types' => $types
                    ];
                    break;
                } else {
                    header('Location: index.php');
                }
            case 'update':
                $instrument = new Instrument();
                $instrument->chargePOST();
                $existing = Instrument::readOne($instrument->id);
                if ($existing && (!empty($_SESSION['id']) && ($_SESSION['id'] == $existing->id_auteur || (($_SESSION['role'] ?? '') == 'administrateur') || (($_SESSION['role'] ?? '') == 'modérateur')))) {
                    if (empty($instrument->id_auteur)) {
                        $instrument->id_auteur = $existing->id_auteur;
                    }
                    $instrument->update();
                    header('Location: controleur.php?page=instrument&action=read&id=' . $instrument->id . '');
                } else {
                    header('Location: index.php');
                }
                break;
            case 'delete':
                $instrument = Instrument::readOne($id);
                if (!($instrument && (!empty($_SESSION['id']) && ($_SESSION['id'] == $instrument->id_auteur || (($_SESSION['role'] ?? '') == 'administrateur') || (($_SESSION['role'] ?? '') == 'modérateur'))))) {
                    header('Location: controleur.php?page=instrument&action=read&id=' . $id . '');
                    break;
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                    Instrument::delete($id);
                    header('Location: controleur.php?page=instrument&action=read');
                    exit();
                } else {
                    $view = 'instrument/instrument_delete_confirm.twig';
                    $data = [
                        'instrument' => $instrument,
                        'titre' => 'Confirmer la suppression de l\'instrument'
                    ];
                }
                break;
        }

        break;
    case 'partition': // annonce de partition
        switch ($action) {
            case 'new':
                // vérif du rôle
                if (!empty($_SESSION['id']) && (($_SESSION['role'] ?? '') == 'musicien' || ($_SESSION['role'] ?? '') == 'administrateur')) {
                    $styles = Style::readAll();
                    $view = 'partition/partition_form.twig';
                    $data = [
                        'titre' => 'Proposer une partition',
                        'styles' => $styles
                    ];
                    break;
                } else {
                    header('Location: index.php');
                }
            case 'create':
                if (!empty($_SESSION['id']) && (($_SESSION['role'] ?? '') == 'musicien' || ($_SESSION['role'] ?? '') == 'administrateur')) {
                    $partition = new Partition();
                    $partition->chargePOST();
                    $partition->id_auteur = $_SESSION['id'];
                    $partition->create();
                    header('Location: controleur.php?page=partition&action=read&id=' . $partition->id . '');
                    break;
                } else {
                    header('Location: index.php');
                }
            case 'read':
                if ($id > 0) {
                    $partition = Partition::readOne($id);
                    $view = 'partition/partition_unique.twig';
                    $data = [
                        'titre' => $partition->nom,
                        'partition' => $partition
                    ];
                } else {
                    $filters = [
                        'id_style' => $_POST['styleFilter'] ?? null,
                        'nom' => $_POST['search'] ?? null,
                        'order' => $_POST['order'] ?? null,
                    ];
                    $partitions = Partition::readAll($filters);
                    $styles = Style::readAll();
                    $view = 'partition/partition_cards.twig';
                    // Les éléments "active" existent pour que les filtres se rappellent les options sélectionnées
                    $data = ['titre' => 'Partitions', 'partitions' => $partitions, 'styles' => $styles, 'activeStyle' => $filters['id_style'], 'activeSearch' => $filters['nom'], 'activeOrder' => $filters['order']];
                }
                break;
            case 'edit':
                $partition = Partition::readOne($id);
                if ($partition && (!empty($_SESSION['id']) && ($_SESSION['id'] == $partition->id_auteur || ($_SESSION['role'] ?? '') == 'administrateur'))) {
                    $styles = Style::readAll();
                    $view = 'partition/partition_form_edit.twig';
                    $data = [
                        'titre' => 'Modification de la partition',
                        'partition' => $partition,
                        'styles' => $styles
                    ];
                    break;
                } else {
                    header('Location: index.php');
                }
            case 'update':
                $partition = new Partition();
                $partition->chargePOST();
                $existing = Partition::readOne($partition->id);
                if ($existing && (!empty($_SESSION['id']) && ($_SESSION['id'] == $existing->id_auteur || ($_SESSION['role'] ?? '') == 'administrateur'))) {
                    if (empty($partition->id_auteur)) {
                        $partition->id_auteur = $existing->id_auteur;
                    }
                    $partition->update();
                    header('Location: controleur.php?page=partition&action=read&id=' . $partition->id . '');
                } else {
                    header('Location: index.php');
                }
                break;
            case 'delete':
                $partition = Partition::readOne($id);
                if (!($partition && (!empty($_SESSION['id']) && ($_SESSION['id'] == $partition->id_auteur || ($_SESSION['role'] ?? '') == 'administrateur')))) {
                    header('Location: controleur.php?page=partition&action=read&id=' . $id . '');
                    break;
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                    Partition::delete($id);
                    header('Location: controleur.php?page=partition&action=read');
                    exit();
                } else {
                    $view = 'partition/partition_delete_confirm.twig';
                    $data = [
                        'partition' => $partition,
                        'titre' => 'Confirmer la suppression de la partition'
                    ];
                }
                break;
        }

        break;
    case 'cours':
        switch ($action) {
            case 'new':
                // vérif du role
                if (!empty($_SESSION['id']) && (($_SESSION['role'] ?? '') == 'musicien' || ($_SESSION['role'] ?? '') == 'administrateur')) {
                    $types = Type::readAll();
                    $view = 'cours/cours_form.twig';
                    $data = [
                        'titre' => 'Ajout de cours',
                        'types' => $types
                    ];
                    break;
                } else {
                    header('Location: index.php');
                }
            case 'create':
                if (!empty($_SESSION['id']) && (($_SESSION['role'] ?? '') == 'musicien' || ($_SESSION['role'] ?? '') == 'administrateur')) {
                    $cours = new Cours();
                    $cours->chargePOST();
                    $cours->id_auteur = $_SESSION['id'];
                    $cours->create();
                    header('Location: controleur.php?page=cours&action=read&id=' . $cours->id . '');
                    break;
                } else {
                    header('Location: index.php');
                }
            case 'read':
                if ($id > 0) {
                    $cours = Cours::readOne($id);
                    $view = 'cours/cours_unique.twig';
                    $data = [
                        'titre' => $cours->nom,
                        'cours' => $cours
                    ];
                } else {
                    $filters = [
                        'id_type' => $_POST['typeFilter'] ?? null,
                        'nom' => $_POST['search'] ?? null,
                        'order' => $_POST['order'] ?? null,
                        'difficulte' => $_POST['difficulteFilter'] ?? null,
                    ];
                    $cours_list = Cours::readAll($filters);
                    $types = Type::readAll();
                    $view = 'cours/cours_cards.twig';
                    // Les éléments "active" existent pour que les filtres se rappellent les options sélectionnées
                    $data = ['titre' => 'Cours', 'cours' => $cours_list, 'types' => $types, 'activeType' => $filters['id_type'], 'activeSearch' => $filters['nom'], 'activeOrder' => $filters['order'], 'activeDifficulte' => $filters['difficulte']];
                }
                break;
            case 'edit':
                $cours = Cours::readOne($id);
                if ($cours && (!empty($_SESSION['id']) && ($_SESSION['id'] == $cours->id_auteur || ($_SESSION['role'] ?? '') == 'administrateur'))) {
                    $types = Type::readAll();
                    $view = 'cours/cours_form_edit.twig';
                    $data = [
                        'titre' => 'Modification du cours',
                        'cours' => $cours,
                        'types' => $types
                    ];
                    break;
                } else {
                    header('Location: index.php');
                }
            case 'update':
                $cours = new Cours();
                $cours->chargePOST();
                $existing = Cours::readOne($cours->id);
                if ($existing && (!empty($_SESSION['id']) && ($_SESSION['id'] == $existing->id_auteur || ($_SESSION['role'] ?? '') == 'administrateur'))) {
                    if (empty($cours->id_auteur)) {
                        $cours->id_auteur = $existing->id_auteur;
                    }
                    $cours->update();
                    header('Location: controleur.php?page=cours&action=read&id=' . $cours->id . '');
                } else {
                    header('Location: index.php');
                }
                break;
            case 'delete':
                $cours = Cours::readOne($id);
                if (!($cours && (!empty($_SESSION['id']) && ($_SESSION['id'] == $cours->id_auteur || ($_SESSION['role'] ?? '') == 'administrateur')))) {
                    header('Location: controleur.php?page=cours&action=read&id=' . $id . '');
                    break;
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                    Cours::delete($id);
                    header('Location: controleur.php?page=cours&action=read');
                    exit();
                } else {
                    $view = 'cours/cours_delete_confirm.twig';
                    $data = [
                        'cours' => $cours,
                        'titre' => 'Confirmer la suppression du cours'
                    ];
                }
                break;
        }

        break;
    case 'panier':
        switch ($action) {
            case 'ajout_instrument':
                // Panier vide par défaut
                $_SESSION['panier_instruments'] ??= [];
                // On ajoute l'id du produit à la fin du tableau panier dans session
                if (!empty($_POST['id_instrument'])) {
                    $_SESSION['panier_instruments'][] = $_POST['id_instrument'];
                    header("Location: controleur.php?page=instrument&action=read&id=" . $_POST['id_instrument']);
                }
                break;
            case 'ajout_partition':
                // Panier vide par défaut
                $_SESSION['panier_partitions'] ??= [];
                // On ajoute l'id du produit à la fin du tableau panier dans session
                if (!empty($_POST['id_partition'])) {
                    $_SESSION['panier_partitions'][] = $_POST['id_partition'];
                    header("Location: controleur.php?page=partition&action=read&id=" . $_POST['id_partition']);
                }
                break;
            case 'ajout_cours':
                // Panier vide par défaut
                $_SESSION['panier_cours'] ??= [];
                // On ajoute l'id du produit à la fin du tableau panier dans session
                if (!empty($_POST['id_cours'])) {
                    $_SESSION['panier_cours'][] = $_POST['id_cours'];
                    header("Location: controleur.php?page=cours&action=read&id=" . $_POST['id_cours']);
                }
                break;
            case 'remove':
                // On retire un élément du panier par son index chargé dans POST
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) && isset($_POST['index'])) {
                    $type = $_POST['type'];
                    $index = intval($_POST['index']);

                    switch ($type) {
                        case 'instrument':
                            $_SESSION['panier_instruments'] ??= [];
                            if (isset($_SESSION['panier_instruments'][$index])) {
                                unset($_SESSION['panier_instruments'][$index]);
                                $_SESSION['panier_instruments'] = array_values($_SESSION['panier_instruments']);
                            }
                            break;
                        case 'partition':
                            $_SESSION['panier_partitions'] ??= [];
                            if (isset($_SESSION['panier_partitions'][$index])) {
                                unset($_SESSION['panier_partitions'][$index]);
                                $_SESSION['panier_partitions'] = array_values($_SESSION['panier_partitions']);
                            }
                            break;
                        case 'cours':
                            $_SESSION['panier_cours'] ??= [];
                            if (isset($_SESSION['panier_cours'][$index])) {
                                unset($_SESSION['panier_cours'][$index]);
                                $_SESSION['panier_cours'] = array_values($_SESSION['panier_cours']);
                            }
                            break;
                    }
                }

                header('Location: controleur.php?page=panier&action=read');
                exit();
            case 'read':
                // Panier vide par défaut
                $_SESSION['panier_instruments'] ??= [];
                $_SESSION['panier_partitions'] ??= [];
                $_SESSION['panier_cours'] ??= [];

                $instruments = [];
                foreach (array_unique($_SESSION['panier_instruments']) as $iid) {
                    $instrument = Instrument::readOne(intval($iid));
                    if ($instrument) {
                        $instruments[] = $instrument;
                    }
                }

                $partitions = [];
                foreach (array_unique($_SESSION['panier_partitions']) as $pid) {
                    $partition = Partition::readOne(intval($pid));
                    if ($partition) {
                        $partitions[] = $partition;
                    }
                }

                $cours_liste = [];
                foreach (array_unique($_SESSION['panier_cours']) as $cid) {
                    $cours = Cours::readOne(intval($cid));
                    if ($cours) {
                        $cours_liste[] = $cours;
                    }
                }

                // calcul des prix (d'abord par sous total puis addition finale)
                $total_instruments = 0.0;
                foreach ($instruments as $it) {
                    $total_instruments += floatval($it->prix ?? 0);
                }

                $total_partitions = 0.0;
                foreach ($partitions as $pt) {
                    $total_partitions += floatval($pt->prix ?? 0);
                }

                $total_cours = 0.0;
                foreach ($cours_liste as $ct) {
                    $total_cours += floatval($ct->prix ?? 0);
                }

                $total = $total_instruments + $total_partitions + $total_cours;

                $view = 'panier/panier_read.twig';
                $data = [
                    'titre' => 'Panier',
                    'instruments' => $instruments,
                    'partitions' => $partitions,
                    'cours' => $cours_liste,
                    'total' => $total
                ];
                break;
        }
}

$twig->addGlobal('session', $_SESSION);

echo $twig->render($view, $data);