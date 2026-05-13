# Wandering Magnolias 
A recipe management web app built with PHP 8, MySQL, and vanilla CSS.

## Stack
- **PHP 8+**
- **MySQL** 
- **CSS**
- **JS** 

## Setup

### 1. Database
```sql
-- Import the schema (creates DB + seeds 6 recipes)
mysql -u root -p < config/schema.sql
```

### 2. Config
Edit `config/database.php` with your credentials:
```php
define('DB_USER', 'your_user');
define('DB_PASS', 'your_password');
```

### 3. Web Server
**Apache** — point `DocumentRoot` to `/public`. `.htaccess` handles routing.

**PHP built-in server** (dev):
```bash
cd public
php -S localhost:8000
```
> Note: query-string routes like `/recipe?id=1` work natively with the built-in server.

### 4. Storage
Ensure `/storage/uploads` is writable:
```bash
chmod 775 storage/uploads
```

## Routes
| Method | Path          | Description          |
|--------|---------------|----------------------|
| GET    | /             | Welcome page         |
| GET    | /login        | Login form           |
| POST   | /login        | Process login        |
| GET    | /register     | Register form        |
| POST   | /register     | Process registration |
| GET    | /logout       | Logout               |
| GET    | /recipes      | Recipe list          |
| GET    | /recipe?id=N  | Single recipe        |
| GET    | /add-recipe   | Add recipe form      |
| POST   | /add-recipe   | Save recipe          |
| GET    | /grocery?id=N | Grocery checklist    |

## Project Structure
```
/app
  /controllers   — request handling + model calls
  /models        — PDO database logic only
  /views         — HTML templates
    /partials    — navbar, footer, head
    /auth        — login, register
    /recipes     — index, show, add
    /grocery     — list
  /handlers      — form processing & validation
  /middleware    — session auth checks
  /core          — Router, Database (PDO singleton)
/config          — DB credentials + schema.sql
/public          — index.php (front controller) + assets
/storage/uploads — user-uploaded recipe images
```

## Fonts
- **DM Sans** — headings
- **Inter** — body text

## Color Palette
| Token       | Value     |
|-------------|-----------|
| `--pink`    | `#E8547A` |
| `--black`   | `#0F0F0F` |
| `--offwhite`| `#FAF8F6` |
