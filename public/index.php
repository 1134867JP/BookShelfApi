<?php
require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Bookshelf\BookController;
use Dotenv\Dotenv;
use Slim\Exception\HttpNotFoundException;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$app = AppFactory::create();

// Middleware para parsing automático de JSON
$app->addBodyParsingMiddleware();

// Middleware de tratamento de erros (modo dev)
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Handler para rotas não encontradas (404)
$errorMiddleware->setErrorHandler(
    HttpNotFoundException::class,
    function ($request, $exception, $displayErrorDetails) use ($app) {
        $response = $app->getResponseFactory()->createResponse();
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'message' => 'Rota não encontrada'
        ], JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
);

// Rotas CRUD
$app->get('/books', BookController::class . ':getAll');
$app->get('/books/{id}', BookController::class . ':getOne');
$app->post('/books', BookController::class . ':create');
$app->put('/books/{id}', BookController::class . ':update');
$app->delete('/books/{id}', BookController::class . ':delete');

$app->run();
