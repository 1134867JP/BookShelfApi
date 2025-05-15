<?php
declare(strict_types=1);

namespace Bookshelf;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;
use PDOException;
use Bookshelf\Database;

class BookController
{
    private function jsonResponse(Response $response, array $payload, int $status): Response
    {
        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    public function getAll(Request $request, Response $response): Response
    {
        try {
            $pdo = Database::getPDO();
            $stmt = $pdo->query('SELECT * FROM books');
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $payload = [
                'status' => 'success',
                'message' => 'Livros recuperados com sucesso',
                'data' => $books
            ];
            $status = 200;
        } catch (PDOException $e) {
            $payload = [
                'status' => 'error',
                'message' => 'Erro ao recuperar livros',
                'error' => $e->getMessage()
            ];
            $status = 500;
        }
        return $this->jsonResponse($response, $payload, $status);
    }

    public function getOne(Request $request, Response $response, array $args): Response
    {
        try {
            $pdo = Database::getPDO();
            $stmt = $pdo->prepare('SELECT * FROM books WHERE id = ?');
            $stmt->execute([$args['id']]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($book) {
                $payload = [
                    'status' => 'success',
                    'message' => 'Livro encontrado',
                    'data' => $book
                ];
                $status = 200;
            } else {
                $payload = [
                    'status' => 'error',
                    'message' => 'Livro não encontrado'
                ];
                $status = 404;
            }
        } catch (PDOException $e) {
            $payload = [
                'status' => 'error',
                'message' => 'Erro ao recuperar livro',
                'error' => $e->getMessage()
            ];
            $status = 500;
        }
        return $this->jsonResponse($response, $payload, $status);
    }

    public function create(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();
        if (
            empty($data['title']) ||
            empty($data['author']) ||
            empty($data['published_year']) ||
            !is_string($data['title']) ||
            !is_string($data['author']) ||
            !is_numeric($data['published_year'])
        ) {
            $payload = [
                'status' => 'error',
                'message' => 'title, author e published_year são obrigatórios e devem ser válidos'
            ];
            return $this->jsonResponse($response, $payload, 400);
        }

        try {
            $pdo = Database::getPDO();
            $stmt = $pdo->prepare(
                'INSERT INTO books (title, author, published_year) VALUES (?, ?, ?)'
            );
            $stmt->execute([
                $data['title'],
                $data['author'],
                (int)$data['published_year']
            ]);
            $id = $pdo->lastInsertId();

            $payload = [
                'status' => 'success',
                'message' => 'Criado com sucesso',
                'data' => ['id' => $id]
            ];
            $status = 201;
        } catch (PDOException $e) {
            $payload = [
                'status' => 'error',
                'message' => 'Erro ao criar livro',
                'error' => $e->getMessage()
            ];
            $status = 500;
        }
        return $this->jsonResponse($response, $payload, $status);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $data = (array)$request->getParsedBody();
        if (
            empty($data['title']) ||
            empty($data['author']) ||
            empty($data['published_year']) ||
            !is_string($data['title']) ||
            !is_string($data['author']) ||
            !is_numeric($data['published_year'])
        ) {
            $payload = [
                'status' => 'error',
                'message' => 'title, author e published_year são obrigatórios e devem ser válidos'
            ];
            return $this->jsonResponse($response, $payload, 400);
        }

        try {
            $pdo = Database::getPDO();
            $stmt = $pdo->prepare(
                'UPDATE books SET title = ?, author = ?, published_year = ? WHERE id = ?'
            );
            $stmt->execute([
                $data['title'],
                $data['author'],
                (int)$data['published_year'],
                $args['id']
            ]);
            if ($stmt->rowCount() > 0) {
                $payload = [
                    'status' => 'success',
                    'message' => 'Atualização realizada com sucesso'
                ];
                $status = 200;
            } else {
                $payload = [
                    'status' => 'error',
                    'message' => 'Livro não encontrado ou dados iguais'
                ];
                $status = 404;
            }
        } catch (PDOException $e) {
            $payload = [
                'status' => 'error',
                'message' => 'Erro ao atualizar livro',
                'error' => $e->getMessage()
            ];
            $status = 500;
        }
        return $this->jsonResponse($response, $payload, $status);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        try {
            $pdo = Database::getPDO();
            $stmt = $pdo->prepare('DELETE FROM books WHERE id = ?');
            $stmt->execute([$args['id']]);
            if ($stmt->rowCount() > 0) {
                $payload = [
                    'status' => 'success',
                    'message' => 'Exclusão realizada com sucesso'
                ];
                $status = 200;
            } else {
                $payload = [
                    'status' => 'error',
                    'message' => 'Livro não encontrado'
                ];
                $status = 404;
            }
        } catch (PDOException $e) {
            $payload = [
                'status' => 'error',
                'message' => 'Erro ao excluir livro',
                'error' => $e->getMessage()
            ];
            $status = 500;
        }
        return $this->jsonResponse($response, $payload, $status);
    }
}
