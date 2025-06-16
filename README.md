# CRIMS v2.0

## (Cocoa Research Information Management System v2.0)

This project is a web application built with Laravel, Vue 3 Composition API, and Inertia.js. It is a modern and efficient stack that enables developers to build scalable and performant web applications.

## Technologies Used

-   **Laravel 8**: a powerful PHP web application framework with an elegant syntax and a rich set of features.
-   **Vue 3 Composition API**: the new and improved way of writing Vue components with better reusability and separation of concerns.
-   **Inertia.js**: a server-side rendering (SSR) solution that allows developers to build single-page applications (SPAs) with the productivity of traditional server-side rendering.

## Project Development Environmet

-   **PHP 7.4**
-   **Composer 2**
-   **NODE v16.13**
-   **NPM 8.1.3**
-   **SQL Server 2019 + SQL Server Management Studio 19**
-   **PHP Extension**:
    php_sqlsrv_73_nts_x64.dll, php_pdo_sqlsrv_73_nts_x64.dll for connection between PHP & SQL Server Database

## Project Structure

The project follows a typical Laravel application structure with additional folders for Vue components and Inertia pages. The Vue components are organized in the `resources/js/Shared` folder, and the Inertia pages are located in the `resources/js/Pages` folder.

## Getting Started

To get started with this project, follow these steps:

1. Clone the repository: `git clone git@gitlab.com:easycode.id/crims-lkm/crims-lkm-panel.git`
2. Install dependencies: `composer install && npm install`
3. Copy the `.env.example` file to `.env` and update the database credentials.
4. Generate an application key: `php artisan key:generate`
5. Migrate the database: `php artisan migrate`
6. Seed the database: `php artisan db:seed`
7. Run the development server: `php artisan serve` and `npm run watch`
