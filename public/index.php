<?php
require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Bookshelf\BookController;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$app = AppFactory::create();

// Middleware para parsing automático de JSON
$app->addBodyParsingMiddleware();

// Middleware de tratamento de erros (modo dev)
$app->addErrorMiddleware(true, true, true);

// Rotas CRUD
$app->get('/books', BookController::class . ':getAll');
$app->get('/books/{id}', BookController::class . ':getOne');
$app->post('/books', BookController::class . ':create');
$app->put('/books/{id}', BookController::class . ':update');
$app->delete('/books/{id}', BookController::class . ':delete');

$app->run();
