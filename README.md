# To-Do API

This project is built with Laravel Framework.

For full framework documentation, please refer to the official Laravel docs: [https://laravel.com/docs/12.x](https://laravel.com/docs/12.x)

---

## 🚀 Setup

### Option 1: Run with Docker (Recommended)

1. Rename the example env files:

   ```
   .env.example --> .env
   src/.env.example --> src/.env
   ```

2. Fill in the missing environment variables in `.env`. Please note that `API_USER` must be email.
3. Start the Docker containers:

   ```
   docker-compose up --build
   ```

### Option 2: Manual Setup (Local Environment)

1. Set up your local PHP(8+), MYSQL(8.0) and Apache/Nginx environment.. Ensure the web server root directory is pointing to `src/public`
2. Rename `.env.example` to `.env`:
3. Either replace `${ENV_VARIABLE}` values directly or set them as environment variables. Please note that `API_USER` must be email.
4. Make sure your local web server user is the owner of `/src/storage` directory or has sufficient write, read, execute permissions otherwise.
5. Run composer

   ```
   composer install
   ```

6. Run Laravel setup:

   ```
   php artisan migrate
   php artisan db:seed --class=BootstrapSeeder
   ```

---

## 📡 API Usage

### Authentication

All requests (except for `/api/auth`) must include:

```http
Accept: application/json
```

Additionally all requests except for `/api/auth` must include:
```http
Authorization: Bearer <your_token>
```

To obtain a token:

```http
POST /api/auth
Content-Type: application/json
Accept: application/json

{
  "email": "you@example.com",
  "password": "your_password"
}
```

- `email` and `password` should match the values `API_USER` and `API_SECRET` environment variables.

### Postman

A complete Postman collection is provided:

- File: `api.postman_collection.json`
- Import it into Postman for quick testing of all endpoints.

---

## ⚙️ Project Structure

```
├── docker/                 # Docker configurations
│   ├── php/                # PHP Dockerfile
│   └── apache/             # Apache vhost config
├── src/                    # Laravel project root
│   ├── app/                # Application logic (Controllers, Models, etc.)
│   ├── routes/             # API routes
│   ├── database/           # Migrations, seeders
|   ├── .env.example        # Laravel-level env vars
│   └── public/             # Entry point for web server
├── docker-compose.yml      # Docker orchestration
├── .env.example            # Docker-level env vars
└── api.postman_collection.json
```

