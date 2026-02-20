<?php
session_start();

include 'include/twig.php';
include 'include/utilisateur.php';
include 'include/article.php';
include 'include/categorie.php';
include 'include/instrument.php';
include 'include/type.php';
include 'include/partition.php';
include 'include/style.php';
include 'include/cours.php';

$twig = init_twig();


$page = isset($_GET['page']) ? $_GET['page'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : 'read';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SESSION['role'] == 'administrateur') {

    switch ($page) {
        case 'comptes':
            switch ($action) {
                case 'read':
                    if ($id > 0) {
                        $user = Utilisateur::readOne($id);
                        // Lire uniquement les articles de cet utilisateur
                        $filters = ['id_auteur' => $user->id];
                        $articles = Article::readAll($filters);
                        $view = 'admin/compte_read_admin.twig';
                        $data = [
                            'user' => $user,
                            'titre' => 'Compte utilisateur',
                            'articles' => $articles
                        ];
                    } else {
                        $filters = [
                            'pseudo' => $_POST['search'] ?? null,
                            'date_creation' => $_POST['date_creation'] ?? null,
                        ];

                        $users = Utilisateur::readAll($filters);
                        $view = 'admin/comptes_liste_admin.twig';
                        $data = [
                            'users' => $users,
                            'titre' => 'Comptes',
                            'activeSearch' => $filters['pseudo'],
                            'activeSort' => $filters['date_creation']
                        ];
                    }
                    break;
                case 'delete':
                    // require a confirmation step before deleting a user
                    if ($id <= 0) {
                        header('Location: backoffice.php?page=comptes&action=read');
                        break;
                    }

                    // If the admin confirmed via POST, perform deletion
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                        Utilisateur::delete($id);
                        header('Location: backoffice.php?page=comptes&action=read');
                        exit();
                    } else {
                        // Otherwise show a confirmation page
                        $user = Utilisateur::readOne($id);
                        $view = 'admin/comptes_delete_confirm.twig';
                        $data = [
                            'user' => $user,
                            'titre' => 'Confirmer la suppression'
                        ];
                    }
                    break;
                case 'update':
                    // handle update from admin form
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $user = new Utilisateur();
                        $user->chargePOST();
                        $user->update();
                        header('Location: backoffice.php?page=comptes&action=read&id=' . $user->id);
                        exit();
                    }
                    break;
            }
            break;
        case 'blog':
            switch ($action) {
                case 'read':
                    if ($id > 0) {
                        $article = Article::readOne($id);
                        $categories = Categorie::readAll();
                        $view = 'admin/blog_read_admin.twig';
                        $data = [
                            'titre' => 'Article (modération)',
                            'article' => $article,
                            'categories' => $categories
                        ];
                    } else {
                        $filters = [
                            'id_categorie' => $_POST['categorieFilter'] ?? null,
                            'titre' => $_POST['search'] ?? null,
                        ];
                        $articles = Article::readAll($filters);
                        $categories = Categorie::readAll();
                        $view = 'admin/blog_list.twig';
                        $data = [
                            'titre' => 'Modération des articles',
                            'articles' => $articles,
                            'categories' => $categories,
                            'activeCategorie' => $filters['id_categorie'],
                            'activeSearch' => $filters['titre']
                        ];
                    }
                    break;
                case 'edit':
                    $article = Article::readOne($id);
                    $categories = Categorie::readAll();
                    $view = 'article/article_form_edit.twig';
                    $data = [
                        'titre' => 'Modification de l\'article',
                        'article' => $article,
                        'categories' => $categories
                    ];
                    break;
                case 'update':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $article = new Article();
                        $article->chargePOST();
                        $article->update();
                        header('Location: backoffice.php?page=blog&action=read&id=' . $article->id);
                        exit();
                    }
                    break;
                case 'delete':
                    $article = Article::readOne($id);
                    if ($id <= 0 || !$article) {
                        header('Location: backoffice.php?page=blog&action=read');
                        break;
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                        Article::delete($id);
                        header('Location: backoffice.php?page=blog&action=read');
                        exit();
                    } else {
                        $view = 'admin/blog_delete_confirm.twig';
                        $data = [
                            'titre' => 'Confirmer la suppression de l\'article',
                            'article' => $article
                        ];
                    }
                    break;
            }
            break;
        case 'magasin':
            header('Location: backoffice.php?page=instrument&action=read');
            break;
        case 'instrument':
            switch ($action) {
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
                            'verif' => $_POST['verif'] ?? null
                        ];

                        $instruments = Instrument::readAll($filters);
                        $types = Type::readAll();
                        $view = 'admin/instrument_cards.twig';
                        $data = [
                            'titre' => 'Instruments',
                            'instruments' => $instruments,
                            'types' => $types,
                            'activeType' => $filters['id_type'],
                            'activeSearch' => $filters['nom'],
                            'activeOrder' => $filters['order'],
                            'activeVerif' => $filters['verif']
                        ];
                    }
                    break;
                case 'publish':
                    $instrument = Instrument::readOne($id);
                    $instrument->verification = 1;
                    $instrument->update();
                    header('Location: backoffice.php?page=instrument&action=read');
                    break;
                case 'edit':
                    $instrument = Instrument::readOne($id);
                    $types = Type::readAll();
                    $view = 'instrument/instrument_form_edit.twig';
                    $data = [
                        'titre' => 'Modification de l\'instrument',
                        'instrument' => $instrument,
                        'types' => $types
                    ];
                    break;
                case 'update':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $instrument = new Instrument();
                        $instrument->chargePOST();
                        $existing = Instrument::readOne($instrument->id);
                        if ($existing) {
                            if (empty($instrument->id_auteur)) {
                                $instrument->id_auteur = $existing->id_auteur;
                            }
                            $instrument->update();
                            header('Location: backoffice.php?page=instrument&action=read&id=' . $instrument->id);
                            exit();
                        } else {
                            header('Location: backoffice.php?page=instrument&action=read');
                            exit();
                        }
                    }
                    break;
                case 'delete':
                    $instrument = Instrument::readOne($id);
                    if ($id <= 0 || !$instrument) {
                        header('Location: backoffice.php?page=instrument&action=read');
                        break;
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                        Instrument::delete($id);
                        header('Location: backoffice.php?page=instrument&action=read');
                        exit();
                    } else {
                        $view = 'admin/instrument_delete_confirm.twig';
                        $data = [
                            'titre' => 'Confirmer la suppression de l\'instrument',
                            'instrument' => $instrument
                        ];
                    }
                    break;
                default:
                    break;
            }
            break;
        case 'partition':
            switch ($action) {
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
                            'order' => $_POST['order'] ?? null
                        ];

                        $partitions = Partition::readAll($filters);
                        $styles = Style::readAll();
                        $view = 'admin/partition_cards.twig';
                        $data = [
                            'titre' => 'Partitions',
                            'partitions' => $partitions,
                            'styles' => $styles,
                            'activeStyle' => $filters['id_style'],
                            'activeSearch' => $filters['nom'],
                            'activeOrder' => $filters['order']
                        ];
                    }
                    break;
                case 'edit':
                    $partition = Partition::readOne($id);
                    $styles = Style::readAll();
                    $view = 'partition/partition_form_edit.twig';
                    $data = [
                        'titre' => 'Modification de la partition',
                        'partition' => $partition,
                        'styles' => $styles
                    ];
                    break;
                case 'update':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $partition = new Partition();
                        $partition->chargePOST();
                        $existing = Partition::readOne($partition->id);
                        if ($existing) {
                            if (empty($partition->id_auteur)) {
                                $partition->id_auteur = $existing->id_auteur;
                            }
                            $partition->update();
                            header('Location: backoffice.php?page=partition&action=read&id=' . $partition->id);
                            exit();
                        } else {
                            header('Location: backoffice.php?page=partition&action=read');
                            exit();
                        }
                    }
                    break;
                case 'delete':
                    $partition = Partition::readOne($id);
                    if ($id <= 0 || !$partition) {
                        header('Location: backoffice.php?page=partition&action=read');
                        break;
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                        Partition::delete($id);
                        header('Location: backoffice.php?page=partition&action=read');
                        exit();
                    } else {
                        $view = 'admin/partition_delete_confirm.twig';
                        $data = [
                            'titre' => 'Confirmer la suppression de la partition',
                            'partition' => $partition
                        ];
                    }
                    break;
                default:
                    break;
            }
            break;
        case 'cours':
            switch ($action) {
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
                            'difficulte' => $_POST['difficulteFilter'] ?? null
                        ];

                        $cours_list = Cours::readAll($filters);
                        $types = Type::readAll();
                        $view = 'admin/cours_cards.twig';
                        $data = [
                            'titre' => 'Cours',
                            'cours' => $cours_list,
                            'types' => $types,
                            'activeType' => $filters['id_type'],
                            'activeSearch' => $filters['nom'],
                            'activeOrder' => $filters['order'],
                            'activeDifficulte' => $filters['difficulte']
                        ];
                    }
                    break;
                case 'edit':
                    $cours = Cours::readOne($id);
                    $types = Type::readAll();
                    $view = 'cours/cours_form_edit.twig';
                    $data = [
                        'titre' => 'Modification du cours',
                        'cours' => $cours,
                        'types' => $types
                    ];
                    break;
                case 'update':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $cours = new Cours();
                        $cours->chargePOST();
                        $existing = Cours::readOne($cours->id);
                        if ($existing) {
                            if (empty($cours->id_auteur)) {
                                $cours->id_auteur = $existing->id_auteur;
                            }
                            $cours->update();
                            header('Location: backoffice.php?page=cours&action=read&id=' . $cours->id);
                            exit();
                        } else {
                            header('Location: backoffice.php?page=cours&action=read');
                            exit();
                        }
                    }
                    break;
                case 'delete':
                    $cours = Cours::readOne($id);
                    if ($id <= 0 || !$cours) {
                        header('Location: backoffice.php?page=cours&action=read');
                        break;
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                        Cours::delete($id);
                        header('Location: backoffice.php?page=cours&action=read');
                        exit();
                    } else {
                        $view = 'admin/cours_delete_confirm.twig';
                        $data = [
                            'titre' => 'Confirmer la suppression du cours',
                            'cours' => $cours
                        ];
                    }
                    break;
                default:
                    break;
            }
            break;
    }

    $twig->addGlobal('session', $_SESSION);
    $twig->addGlobal('is_admin', true);

    echo $twig->render($view, $data);

} elseif ($_SESSION['role'] == 'modérateur') {
    switch ($page) {
        case 'magasin':
            header('Location: backoffice.php?page=instrument&action=read');
            break;
        case 'instrument':
            switch ($action) {
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
                            'verif' => $_POST['verif'] ?? null
                        ];

                        $instruments = Instrument::readAll($filters);
                        $types = Type::readAll();
                        $view = 'admin/instrument_cards.twig';
                        $data = [
                            'titre' => 'Instruments',
                            'instruments' => $instruments,
                            'types' => $types,
                            'activeType' => $filters['id_type'],
                            'activeSearch' => $filters['nom'],
                            'activeOrder' => $filters['order'],
                            'activeVerif' => $filters['verif']
                        ];
                    }
                    break;
                case 'publish':
                    $instrument = Instrument::readOne($id);
                    $instrument->verification = 1;
                    $instrument->update();
                    header('Location: backoffice.php?page=instrument&action=read');
                    break;
                case 'edit':
                    $instrument = Instrument::readOne($id);
                    $types = Type::readAll();
                    $view = 'instrument/instrument_form_edit.twig';
                    $data = [
                        'titre' => 'Modification de l\'instrument',
                        'instrument' => $instrument,
                        'types' => $types
                    ];
                    break;
                case 'update':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $instrument = new Instrument();
                        $instrument->chargePOST();
                        $existing = Instrument::readOne($instrument->id);
                        if ($existing) {
                            if (empty($instrument->id_auteur)) {
                                $instrument->id_auteur = $existing->id_auteur;
                            }
                            $instrument->update();
                            header('Location: backoffice.php?page=instrument&action=read&id=' . $instrument->id);
                            exit();
                        } else {
                            header('Location: backoffice.php?page=instrument&action=read');
                            exit();
                        }
                    }
                    break;
                case 'delete':
                    $instrument = Instrument::readOne($id);
                    if ($id <= 0 || !$instrument) {
                        header('Location: backoffice.php?page=instrument&action=read');
                        break;
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                        Instrument::delete($id);
                        header('Location: backoffice.php?page=instrument&action=read');
                        exit();
                    } else {
                        $view = 'admin/instrument_delete_confirm.twig';
                        $data = [
                            'titre' => 'Confirmer la suppression de l\'instrument',
                            'instrument' => $instrument
                        ];
                    }
                    break;
                default:
                    break;
            }
    }

    $twig->addGlobal('session', $_SESSION);
    $twig->addGlobal('is_admin', true);

    echo $twig->render($view, $data);

} else {
    header('Location: index.php');
}

