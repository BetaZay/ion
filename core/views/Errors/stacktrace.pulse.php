<!DOCTYPE html>
<html>
<head>
    <title>Exception</title>
    <style>
        body {
            font-family: monospace;
            background: #1e1e1e;
            color: #eee;
            padding: 2rem;
        }

        h1 { color: #f26; }

        .container {
            display: flex;
            gap: 2rem;
        }

        .panel {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .code-preview, pre {
            background: #2a2a2a;
            padding: 1rem;
            border-radius: 4px;
            overflow-x: auto;
        }

        .code-preview-line {
            white-space: pre;
            display: flex;
            align-items: center;
            min-height: 1.5em;
        }

        .highlight {
            background: #4d3333;
            color: #fff;
        }

        .line-num {
            color: #888;
            display: inline-block;
            width: 3em;
            text-align: right;
            margin-right: 1em;
            flex-shrink: 0;
        }

        .info {
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>

<h1>Uncaught Exception</h1>

<div class="info">
    <p><strong>{{ $class }}:</strong> {{ $message }}</p>
    <p><strong>File:</strong> {{ $file }}</p>
    <p><strong>Line:</strong> {{ $line }}</p>
</div>

<div class="container">
    <div class="panel">
        <h2>Stack Trace</h2>
        <pre>{{ $trace }}</pre>
    </div>
    <div class="panel">
        <h2>Code Preview</h2>
        <div class="code-preview">
            @foreach ($preview as $line)
            <div class="code-preview-line @if($line['highlight']) highlight @endif">
                <span class="line-num">{{ $line['line'] }}</span>{{ $line['code'] }}
            </div>
            @endforeach
        </div>
    </div>
</div>

</body>
</html>
