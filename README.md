# Mini CRM

Mini CRM for collecting and processing ticket requests via a universal widget.

## Requirements

- PHP 8.4+
- PostgreSQL 16+
- Composer
- Node.js 18+ (for building assets)

## Tech Stack

- **Framework:** Laravel 12
- **Database:** PostgreSQL
- **Authentication:** Laravel Breeze (Blade)
- **Media Library:** Spatie Media Library
- **Permissions:** Spatie Laravel Permission

## Installation

### Local Setup

1. Clone the repository:
```bash
git clone <repository-url>
cd mini-crm
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node dependencies and build assets:
```bash
npm install
npm run build
```

4. Copy environment file and configure:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure database in `.env`:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=mini_crm
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

6. Run migrations and seeders:
```bash
php artisan migrate --seed
```

7. Create storage link:
```bash
php artisan storage:link
```

8. Start the development server:
```bash
php artisan serve
```

### Docker Setup

1. Copy environment file:
```bash
cp .env.example .env
```

2. Configure database for Docker in `.env`:
```
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=mini_crm
DB_USERNAME=postgres
DB_PASSWORD=secret
```

3. Start containers:
```bash
docker-compose up -d
```

4. Install dependencies and setup:
```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app php artisan storage:link
```

5. Access the application at http://localhost:8080

## Default Users

After running seeders, the following users are available:

| Role    | Email               | Password |
|---------|---------------------|----------|
| Admin   | admin@example.com   | password |
| Manager | manager@example.com | password |

## Features

### Widget (`/widget`)
- Embeddable contact form for external websites
- Supports file attachments (up to 5 files, 10MB each)
- Phone validation in E.164 format
- Rate limiting: 1 ticket per day per contact

### Admin Panel (`/admin/tickets`)
- List all tickets with filtering
- View ticket details
- Update ticket status
- Download attachments
- Access restricted to admin/manager roles

### API Endpoints

| Method | Endpoint               | Description           | Auth     |
|--------|------------------------|-----------------------|----------|
| POST   | `/api/tickets`         | Create a new ticket   | None     |
| GET    | `/api/tickets/statistics` | Get ticket statistics | Sanctum  |

See `swagger.yaml` for full API documentation.

## Testing

Run the test suite:
```bash
php artisan test
```

Run with coverage:
```bash
php artisan test --coverage
```

## Project Structure

```
app/
├── Enums/              # TicketStatus enum
├── Http/
│   ├── Controllers/
│   │   ├── Admin/      # Admin panel controllers
│   │   └── Api/        # API controllers
│   ├── Middleware/     # Custom middleware
│   ├── Requests/       # Form request validation
│   └── Resources/      # API resources
├── Models/             # Eloquent models
├── Repositories/       # Repository pattern implementation
├── Services/           # Business logic services
└── Providers/          # Service providers

database/
├── factories/          # Model factories
├── migrations/         # Database migrations
└── seeders/            # Database seeders

resources/views/
├── admin/              # Admin panel views
└── widget/             # Widget view

tests/
├── Feature/            # Feature tests
│   ├── Admin/          # Admin feature tests
│   └── Api/            # API feature tests
└── Unit/               # Unit tests
```

## License

This project is open-sourced software licensed under the MIT license.
