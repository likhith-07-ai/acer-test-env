# Project Setup

## Prerequisites

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM >= 9.x
- MySQL or SQLite

## Installation Steps

### Step 1: Install PHP Dependencies

```bash
composer install
```

### Step 2: Install Node Dependencies

```bash
npm install
```

### Step 3: Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Database Setup

**Option A: SQLite**

1. Update `.env` file:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/Applications/XAMPP/xamppfiles/htdocs/acer-ratings/database/database.sqlite
```

2. Create database file:
```bash
touch database/database.sqlite
```

3. Run migrations:
```bash
php artisan migrate
```

**Option B: MySQL**

1. Create database in MySQL (via phpMyAdmin or command line)

2. Update `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

3. Run migrations:
```bash
php artisan migrate
```

### Step 5: Build Assets

**For Production:**
```bash
npm run build
```

**For Development:**
```bash
npm run dev
```

### Step 6: Start Server

```bash
php artisan serve
```

## Quick Setup (All Commands)

```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup (SQLite)
touch database/database.sqlite
php artisan migrate

# Build assets
npm run build

# Start server
php artisan serve
```
