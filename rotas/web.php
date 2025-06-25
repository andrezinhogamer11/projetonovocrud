<?php
use Slim\Views\PhpRenderer;

$app->get('/login', function ($request, $response) {
    $renderer = new PhpRenderer(__DIR__.'/../views/login');
    return $renderer->render($response, 'login.php');
});

$app->get('/cadastrar', function ($request, $response) {
    $renderer = new PhpRenderer('../views/login');
    return $renderer->render($response, 'cadastrar.php');
});
$app->get('/esqueci-minha-senha', function ($request, $response) {
    $renderer = new PhpRenderer('../views/login');
    return $renderer->render($response, 'esqueci-minha-senha.php');
});