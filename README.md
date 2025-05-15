# Trabalho G2: API de Gerenciamento de Livros

**Nanodegree:** Arquitetura de Hardware  
**Disciplina:** Sistemas Operacionais  
**Semestre:** 2025.1  
**Data de entrega:** 22/05/2025  
**Professor:** M.Sc. Fernando P. Pinheiro  
**E-mail:** fernando.pinheiro@atitus.edu.br  

---

## Integrantes  
- João Pedro Rodrigues  
- Ricardo Basso Gunther  
- Ricardo da Silva Groth  
- Nycolas Musskopf Fachi  
- Fabricio Panisson  
- Jean de Cesare  

---

## Descrição do Projeto  
Esta aplicação é uma **API RESTful de Gerenciamento de Livros**, dividida em um único recurso (`books`) que oferece operações completas de CRUD (Create, Read, Update, Delete). A API roda em contêineres Docker orquestrados por Docker Compose, expondo uma porta HTTP para consumo por clientes (Postman, frontend web, mobile, etc.).

---

## Tecnologias e Componentes  

- **PHP 8.1-CLI**  
- **Slim Framework 4** (router, middleware)  
- **MySQL 8.0** (persistência de dados)  
- **Docker & Docker Compose** (containerização e orquestração)  
- **vlucas/phpdotenv** (carregamento de variáveis de ambiente)  

---

## Endpoints (`/books`)  

| Método | Rota             | Descrição                             | Status de Retorno |
|-------:|------------------|---------------------------------------|-------------------|
| `GET`  | `/books`         | Lista todos os livros                 | `200 OK`          |
| `GET`  | `/books/{id}`    | Retorna um livro específico por ID    | `200 OK` / `404`  |
| `POST` | `/books`         | Cria um novo livro                    | `201 Created`     |
| `PUT`  | `/books/{id}`    | Atualiza dados de um livro existente  | `200 OK`          |
| `DELETE`| `/books/{id}`   | Remove um livro                       | `200 OK`          |

Exemplo de payload para criação/atualização (JSON):
```json
{
  "title": "Título do Livro",
  "author": "Nome do Autor",
  "published_year": 2025
}
