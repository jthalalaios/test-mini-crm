This mini CRM contains:
Docker containers to build whole project


Admin panel to manage companies
Basic project to manage companies and their employees. Mini-CRM.
Basic Laravel Auth: ability to log in as administrator
Database seeds to create first user with email admin@admin.com and password "password"
CRUD functionality (Create / Read / Update / Delete) for two menu items: Companies and Employees.
Companies DB table consists of these fields: Name (required), email, logo (minimum 100x100), website
Employees DB table consists of these fields: First name (required), last name (required), Company (foreign key to Companies), email, phone
Database migrations to create those schemas above
Store companies logos in storage/app/public folder and make them accessible from public
Use basic Laravel resource controllers with default methods - index, create, store etc.
Laravel's validation function, using Request classes
Laravel's pagination for showing Companies/Employees list, 10 entries per page
Laravel's starter kit for auth and basic theme, but remove ability to register


Extra Task for "Advanced" Juniors
If you feel like this task is too small and simple, you can add these things on top:
Use Datatables.net library to show table - with our without server-side rendering
Use more complicated front-end theme like AdminLTE
Email notification: send email whenever new company is entered (used gmail as smtp)
project multi-language (using lang folder)


Its not having testing....



