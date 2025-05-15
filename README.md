# Trabalho G2: Conteinerização – API de Livros (v2)

**Nanodegree:** Arquitetura de Hardware  
**Disciplina:** Sistemas Operacionais  
**Semestre:** 2025.1  
**Dias:** Segunda a Quarta-feira  
**Data de entrega:** 22/05/2025  
**Professor:** M.Sc. Fernando P. Pinheiro  
**E-mail:** fernando.pinheiro@atitus.edu.br  

## Integrantes  
- Alisson Silva  
- Bruno Costa  
- João Pedro  

## Descrição  
API RESTful para gerenciar um catálogo de livros. Roda em Docker, agora com extensão PDO MySQL instalada.

## Configuração Docker Compose  
No serviço **app**, adicionamos:
```bash
docker-php-ext-install pdo_mysql
```
antes de `composer update`, garantindo que o PDO MySQL esteja disponível.

## Execução  
```bash
docker compose down --volumes
docker compose up --build
```

Acesse `http://localhost:8081/books`.
