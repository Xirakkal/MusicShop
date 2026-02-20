<?php


include('include/twig.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// $_SESSION['user'] = "test_user";

$twig = init_twig();

// expose current user (if any) to all templates so Twig can check `user`
$twig->addGlobal('session', $_SESSION);

echo $twig->render('base.twig', [
    'titre' => 'Page d\'accueil',
]);
