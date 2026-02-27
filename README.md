This mini CRM contains:

Docker setup to build whole project,
It is necessary to navigate into the Laravel project directory and execute the following commands manually. Attempts to automate composer install and npm install during the Docker image build were unsuccessful, despite 13 hours of development effort.

- composer install (php 8.4)
- npm install (node20)

after that cd on folder's parent = on main /test_mini_stack/
- docker compose build --no-cache
- docker compose up -d

Once the docker containers are up:
docker exec -it laravel-app bash
php artisan migrate:fresh
php artisan db:seed

Core Features:

Manage companies and their employees (Mini-CRM functionality)

Laravel authentication for administrator login (registration disabled)

Database seeding creates an initial admin user:

Email: admin@admin.com

Password: password

CRUD operations (Create, Read, Update, Delete) for Companies and Employees

Database Schema:

Companies Table: Name (required), Email, Logo (minimum 100x100), Website

Employees Table: First Name (required), Last Name (required), Company (foreign key), Email, Phone

Migrations are included to generate the above schemas

Company logos are stored in storage/app/public and accessible via the public directory

Application Features:

Uses Laravel resource controllers with standard methods (index, create, store, etc.)

Form validation implemented using Laravel Request classes

Pagination for listing Companies and Employees (10 entries per page)

Datatables.net integration for displaying tables (with optional server-side rendering)

Front-end utilizes a more advanced theme, such as AdminLTE

Email notifications sent via Gmail SMTP when a new company is added

Multi-language support using the lang folder

Its not having testing methods....



