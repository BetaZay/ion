<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8" />
    <title>{{ $title ?? 'Untitled' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground min-h-screen flex flex-col items-center justify-center p-6">
    <header class="mb-6 w-full max-w-3xl"><?= $slots['header'] ?? '' ?></header>
    <main class="w-full max-w-3xl"><?= $slot ?></main>
</body>
</html>