# INF653 Midterm Project – PHP OOP REST API

This is the midterm project for the **INF653 – Back-End Web Development I** course. The goal was to build a fully functional REST API using PHP (OOP style) and PostgreSQL, following proper CRUD operations and RESTful practices.

### ✅ Live API URL
[https://midterm-rute.onrender.com/api](https://midterm-rute.onrender.com/api)

---

## 🚀 Features

- Built with **PHP 8.2**, **PostgreSQL**, and **Docker**
- RESTful API structure with full CRUD support
- Three main entities:
  - `quotes`
  - `authors`
  - `categories`
- OOP class-based structure
- Error handling and validation
- JSON responses with appropriate HTTP headers
- Passed **100% of Netlify automated tests (63/63)** ✅

---

## 📁 Project Structure

midterm/
│
├── api/
│   ├── authors/
│   │   ├── create.php
│   │   ├── delete.php
│   │   ├── index.php
│   │   ├── read.php
│   │   ├── update.php
│   │
│   ├── categories/
│   │   ├── create.php
│   │   ├── delete.php
│   │   ├── index.php
│   │   ├── read.php
│   │   ├── update.php
│   │
│   ├── quotes/
│   │   ├── create.php
│   │   ├── delete.php
│   │   ├── index.php
│   │   ├── read.php
│   │   ├── update.php
│
├── config/
│   └── Database.php
│
├── models/
│   ├── Author.php
│   ├── Category.php
│   ├── Quote.php
│
├── vendor/              # Composer dependencies (e.g., vlucas/phpdotenv)
│
├── .env                 # Environment variables (ignored in Git)
├── .env.local           # Optional local environment config
├── .gitignore
├── composer.json
├── Dockerfile           # Docker config for deployment
├── render.yaml          # Render.com deployment config
└── README.md            # Project documentation



---
/api /quotes - index.php - read.php - create.php - update.php - delete.php /authors /categories /config

Database.php /models

Quote.php

Author.php

Category.php /.env /Dockerfile /render.yaml

## 🧪 API Endpoints

### Quotes
- `GET /api/quotes/` – Get all quotes
- `GET /api/quotes/?id=10` – Get single quote
- `GET /api/quotes/?author_id=5&category_id=4` – Filtered quotes
- `POST /api/quotes/` – Create a new quote
- `PUT /api/quotes/` – Update a quote
- `DELETE /api/quotes/` – Delete a quote

### Authors
- `GET /api/authors/`
- `GET /api/authors/?id=5`
- `POST /api/authors/`
- `PUT /api/authors/`
- `DELETE /api/authors/`

### Categories
- `GET /api/categories/`
- `GET /api/categories/?id=4`
- `POST /api/categories/`
- `PUT /api/categories/`
- `DELETE /api/categories/`

---

## 📦 Technologies Used

- PHP 8.2
- PostgreSQL (Hosted on Render.com)
- Docker (for deployment)
- Netlify (for testing)
- Postman (for local testing)
- Git & GitHub

---

## 📌 Environment Variables

`.env` or `.env.local` file (not included in repo):

```env
DB_HOST=db-host
DB_PORT=5432
DB_NAME=quotesdb
DB_USER=db-user
DB_PASS=db-password

Assignment Requirements Met
✅ OOP PHP models for each table

✅ Full CRUD functionality for quotes, authors, categories

✅ Dynamic query filters (author_id, category_id)

✅ Database seeded with 25+ quotes, 5+ authors, 5+ categories

✅ Robust error handling and validation

✅ Deployment on Render with Docker

✅ Passed all 63 Netlify tests

📚 License
This project is for educational purposes as part of the INF653 Midterm. No license applied.


