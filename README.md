# Lost & Found Application

A web application for managing lost and found items built with Laravel (Backend) and Vue.js (Frontend).

## Prerequisites

- PHP >= 8.2
- Node.js >= 16
- Composer
- MySQL/MariaDB
- Git

## Installation

### Backend Setup

1. Navigate to backend directory:

```bash
cd backend
```

2. Install PHP dependencies:

```bash
composer install
```

3. Create environment file:

```bash
cp .env.example .env
```

4. Configure your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

JWT_SECRET=your_jwt_secret
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Generate JWT secret:

```bash
php artisan jwt:secret
```

7. Create storage link:

```bash
php artisan storage:link
```

8. Run database migrations:

```bash
php artisan migrate
```

9. Start the development server:

```bash
php artisan serve
```

### Frontend Setup

1. Navigate to frontend directory:

```bash
cd frontend
```

2. Install Node.js dependencies:

```bash
npm install
```

3. Start the development server:

```bash
npm run dev
```

## Usage

- Backend API will be available at: `http://localhost:8000`
- Frontend application will be available at: `http://localhost:5173`

## Features

- User authentication (Login/Register)
- Create lost/found item reports
- Upload images for items
- Update item status
- Chat system for item claims
- Admin dashboard for management

## Directory Structure

```
lostnfound/
├── backend/         # Laravel API
│   ├── app/
│   ├── config/
│   ├── database/
│   └── ...
└── frontend/        # Vue.js application
    ├── src/
    ├── public/
    └── ...
```

## Common Issues

1. Storage permissions:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

2. If images don't appear:

```bash
php artisan storage:link
```

3. Database connection issues:

- Verify database credentials in `.env`
- Ensure MySQL service is running

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request
