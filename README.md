This mini CRM contains:

Docker setup to build whole project,
cd on folder's parent = on main /test_mini_stack/
- docker compose build --no-cache
- docker compose up -d

Once the docker containers are up, wait 1 min and after access the application by navigating to:
http://localhost:8080/

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



