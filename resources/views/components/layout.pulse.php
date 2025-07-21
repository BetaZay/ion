<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Untitled' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header><?= $slots['header'] ?? '' ?></header>
    <main><?= $slot ?></main>
</body>
</html>