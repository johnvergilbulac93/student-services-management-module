# Student Service Management Module (SSMM)

This repository contains the **Student Service Management Module** consisting of a backend and frontend.

---

## Backend Setup (SSMM-BACKEND)

1. Navigate to the backend folder:

```bash
cd ssmm-backend
```

2. Install dependencies:

```bash
composer install
```

3. Run migrations:

```bash
php artisan migrate
```

4. Seed the database:

```bash
php artisan db:seed
```

5. Run the development server:

```bash
composer run dev
```

---

## Frontend Setup (SSMM-FRONTEND)

1. Navigate to the frontend folder:

```bash
cd ssmm-frontend
```

2. Install dependencies:

```bash
npm install
```

3. Run the development server:

```bash
npm run dev
```

---

## Packages Used

### Laravel Packages
- Laravel Sanctum
- Laravel Excel

### NPM Packages
- flowbite
- axios
