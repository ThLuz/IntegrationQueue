<p align="center">
  <img src="https://laravel.com/img/logomark.min.svg" width="120" alt="Laravel Logo" />
</p>

<h1 align="center">Fila de Integração de Clientes</h1>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue" />
  <img src="https://img.shields.io/badge/Laravel-10-red" />
  <img src="https://img.shields.io/badge/Queue-Redis-orange" />
  <img src="https://img.shields.io/badge/Made%20by-ThLuz-success" />
  <img src="https://img.shields.io/badge/Project-Backend%20Integration-blueviolet" />
</p>

---

## ℹ️ Sobre o Projeto

**Fila de Integração de Clientes** é um **mini-sistema backend em Laravel** que simula o processamento assíncrono de integrações com sistemas externos utilizando **filas (Queue) e Redis**.

O sistema permite:

- Criar solicitações de integração
- Processar de forma assíncrona
- Consultar status em tempo real
- Reexecutar automaticamente em caso de falha (retry)
- Visualizar os últimos jobs em uma interface simples com Blade

---

## 🚀 Requisitos

- PHP >= 8.2  
- Composer  
- MySQL ou MariaDB  
- Redis  
- Docker e Docker Compose (opcional, recomendado)  

---

## 💻 Tecnologias Utilizadas

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/Redis-DC382D?style=for-the-badge&logo=redis&logoColor=white"/>
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
  <img src="https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white"/>
</p>

---

## 🎨 Layout 

Pré-visualização do layout da página:

<p align="center">
  <img src="https://i.imgur.com/itbExp7.png" alt="Teste prático Layout Preview"/>
</p>

---

## ▶️ Começando (Com Docker)

```bash
git clone https://github.com/ThLuz/TechnicalTest.git
cd TechnicalTest/backend
cp .env.example .env
# Edite o .env e defina:
# DB_PASSWORD=root
cd ../infra
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan queue:work redis --sleep=3 --tries=3
```

---

## ▶️ Começando (Sem Docker)

```bash
git clone https://github.com/ThLuz/TechnicalTest.git
cd TechnicalTest/backend
cp .env.example .env
# Edite o .env e defina:
# DB_PASSWORD=root
# DB_HOST=127.0.0.1
composer install
php artisan key:generate
php artisan migrate
php artisan serve
php artisan queue:work redis --sleep=3 --tries=3
```

---

## 🔹 API Endpoints

### Criar Integração
POST /api/integrations/customers

Request:
{
  "external_id": "123",
  "nome": "Fulano da Silva",
  "cpf": "12345678901",
  "email": "fulano@email.com"
}

Response:
{
  "id": 1,
  "status": "PENDING"
}

---

### Consultar Status
GET /api/integrations/customers/{id}

---

### Listar Últimos Jobs
GET /api/integrations/customers

Filtros (query params opcionais):

- status → PENDING | PROCESSING | SUCCESS | ERROR  
- external_id → filtra pelo ID externo

Exemplo:
GET /api/integrations/customers?status=ERROR&external_id=123

---

### Fake Integração Externa
POST /fake/external/customers

Regras:
- external_id par → sucesso (200)
- external_id ímpar → erro (500)

---

## 🔄 Processamento Assíncrono

Job: ProcessIntegrationJob

Status:
- PENDING
- PROCESSING
- SUCCESS
- ERROR

Retry automático:
- 3 tentativas
- 10s, 30s, 60s

## 🖥 Interface Web

Rota: /integrations  
Exibe: ID, External ID, Status, Tentativas, Data de processamento

## 📝 Logs

storage/logs/laravel.log

## ✅ Testes

php artisan test
