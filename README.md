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
3. Create a schema/database name of your database choice: 
```bash
e.g sssm-app
```
4. Run migrations:

```bash
php artisan migrate
```

5. Seed the database:

```bash
php artisan db:seed
```

6. Run the development server:

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
- Ably Realtime

### NPM Packages

- flowbite
- axios
- vueuse/core
- lodash

### API Testing

You can use the postman collection that can be found in the repo.

### Notes

You can use the sample data that can be found in the repo.

## Architecture Used

Microservices - independent services, each responsible for a single business capability. Each service can be developed, deployed, and scaled independently.
