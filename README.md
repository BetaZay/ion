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

# Console Commands (Forge)

Ion includes a CLI system called Forge, accessible via `./forge.php`.

Automatically loads and registers commands from:

- `core/console/`
- `app/console/`

Supports arguments and flags.

Built-in command listing via:

```bash
php forge.php list
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
php forge.php hello
```

### Directory Structure

```
project-root/
├── Core/                 # Framework internals
│   ├── Console/
│   ├── Controllers/
│   ├── Http/
│   ├── Models/
│   └── Support/
├── App/                  # User application (models, commands, etc.)
├── Config/               # Configuration files
│   ├── app.php
│   └── database.php
├── Database/             # Migrations and seeders
├── Public/               # Entry point (index.php) and public assets
├── Resources/            # Views, routes, UI components
│   ├── Views/
│   └── Routes/
├── Storage/              # Logs, compiled views, runtime data
├── autoload.php          # Custom PSR-4 autoloader
├── forge.php             # Application bootstrap
└── .env                  # Environment variables
```

### Additional Features

- HTTP request/response abstraction
- Middleware support (e.g., session handling)
- Customizable view error pages (404, 500)
- Supports dynamic layout of future modules like CLI, jobs, services

## Getting Started

Clone the repository or generate a project.

Run a PHP server:

```
php -S localhost:8000 -t Public
```

Edit routes in `resources/routes/web.php`.

Edit views in `resources/views/`.

