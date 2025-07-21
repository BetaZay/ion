# Ion PHP Framework

Ion is a lightweight, modular PHP framework built for simplicity, performance, and structure. It draws inspiration from Laravel’s organization while remaining minimal and fully customizable.

---

## Features

### Routing

- Simple and intuitive route definitions using closures.
- Supports all standard HTTP verbs: GET, POST, PATCH, PUT, DELETE.
- Route files are located in `resources/routes/`.
- Example:
  ```php
  $router->get('/', fn () => View::render('welcome'));
  ```

### Pulse Templating Engine

Custom lightweight templating engine with .pulse.php files.

Blade-inspired syntax:

- `{{ $var }}` – escaped output
- `@if`, `@elseif`, `@else`, `@endif`
- `@foreach`, `@endforeach`
- `@php`, `@endphp`

Views are compiled and cached in `storage/views/` for performance.

Supports both application and core views:

- Resources/Views/ for user views
- Core/Views/ for internal system views

### Error Handling

Centralized exception and error handling via `Core\Support\ErrorHandler`.

If `APP_DEBUG=true`, a custom debug screen is rendered showing:

- Exception class and message
- File and line of failure
- Stack trace
- Highlighted source preview, Laravel-style

Graceful fallback to generic error views (e.g., `500.pulse.php`) if debug is disabled.

### Environment Configuration

`.env` file support for runtime config.

Access via `Env::get('KEY')`.

Used for toggles like debug mode (`APP_DEBUG=true`).

# Console Commands (Spark)

Ion includes a CLI system called Spark, accessible via `./spark.php`.

Automatically loads and registers commands from:

- `core/console/`
- `app/console/`

Supports arguments and flags.

Built-in command listing via:

```bash
php spark list
```

Example command structure:

```php
class Hello implements ConsoleCommand {
    public function name(): string {
        return 'hello';
    }

    public function handle(array $args): void {
        echo "Hello, world!\n";
    }
}
```

Registered commands are executed like so:

```bash
php spark hello
```

### Directory Structure

```
ion/
├── core/                 # Framework internals
│   ├── bootstrap/
│   ├── console/
│   ├── contracts/
│   ├── database/
│   ├── http/
│   ├── logging/
│   ├── middleware/
│   ├── models/
│   ├── support/
│   └── views/
├── app/                  # User application (models, commands, etc.)
├── config/               # Configuration files
│   ├── app.php
│   └── database.php
├── database/             # Migrations and seeders
│   ├── migrations/
│   └── seeders/
├── public/               # Entry point (index.php) and public assets
│   ├── index.php
├── resources/            # Views, routes, UI components
│   ├── css/
│   ├── js/
│   ├── routes/
│   └── views/
├── storage/              # Logs, compiled views, runtime data
├── autoload.php          # PSR-4 autoloader
├── spark.php             # CLI
└── .env                  # Environment variables
```

### Additional Features

- HTTP request/response abstraction
- Middleware support (e.g., session handling)
- Customizable view error pages (404, 500)
- Supports dynamic layout of future modules like CLI, jobs, services

## Getting Started

Clone the repository or generate a project.

Run composer and & npm install:

```
composer install
npm install
```

Run a PHP server:

```
php spark serve args: --port={port} --host={host ip}
```

Run vite:

```
npm run dev
```

Build vite:

```
npm run build
```

Edit routes in `resources/routes/app.php`.

Edit views in `resources/views/`.

