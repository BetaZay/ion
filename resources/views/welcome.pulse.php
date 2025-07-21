<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Ion</title>

    <style>
        body {
            background: #0f172a;
            color: #e2e8f0;
            font-family: sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            text-align: center;
            padding: 2rem;
        }
        h1 {
            font-size: 3rem;
            color: #38bdf8;
            margin-bottom: 0.5rem;
        }
        p {
            font-size: 1.1rem;
            color: #94a3b8;
            margin: 0.25rem 0;
        }
        code {
            background: #1e293b;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.95rem;
            color: #facc15;
        }
        footer {
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #64748b;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<h1>Welcome to Ion</h1>
<p>Your custom PHP framework is running.</p>
<p>Edit this view at <code>resources/views/welcome.pulse.php</code></p>
<p>Routing is defined in <code>resources/routes/web.php</code></p>
<p>Vite dev assets are loaded if <code>APP_ENV=local</code></p>

<footer>
    Ion Framework · Pulse Engine · Spark CLI
</footer>
</body>
</html>
