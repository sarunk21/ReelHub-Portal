# ReelHub Portal

A modern movie discovery web application built with Laravel 5.8 and OMDb API integration.

## Features

- **Movie Discovery**: Browse popular movies with infinite scroll pagination.
- **Search**: Real-time server-side search functionality (default: Batman).
- **Movie Details**: Comprehensive movie information including plot, director, actors, and ratings.
- **Favorites System**: 
  - Add/Remove movies to your personal favorites list.
  - Quick access via "My Favorites" menu.
- **Authentication**: 
  - Custom Username-based login (e.g., `aldmic`).
  - Route protection for movie details and favorites.
- **Multi-Language Support**: 
  - Switch between English (EN) and Indonesian (ID).
  - Localized interface elements.
- **Performance**: 
  - Server-side caching for API requests (60 minutes).
  - Lazy loading for images.

## Technology Stack

- **Backend**: Laravel 5.8 (PHP 7.4)
- **Database**: MySQL 5.7
- **Frontend**: Blade Templates, Bootstrap 5, jQuery
- **API**: OMDb API
- **Environment**: Docker (Laradock / Custom Compose)

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/reelhub-portal.git
   cd reelhub-portal
   ```

2. **Setup Environment**
   ```bash
   cp .env.example .env
   # Configure your DB_database, DB_USERNAME, etc. in .env
   # Add your OMDB_API_KEY in .env
   ```

3. **Install Dependencies**
   ```bash
   docker-compose run --rm app composer install
   ```

4. **Generate Key**
   ```bash
   docker-compose run --rm app php artisan key:generate
   ```

5. **Run Migrations & Seeders**
   ```bash
   docker-compose run --rm app php artisan migrate --seed
   ```

6. **Start Application**
   ```bash
   docker-compose up -d
   ```

   Visit `http://localhost:8080` in your browser.

## Credentials

- **Username**: `aldmic`
- **Password**: `password` (or as defined in seeder)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
