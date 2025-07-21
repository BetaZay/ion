<?php

namespace core\support;

use core\pulse\View;

class ErrorHandler
{
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        set_exception_handler(fn($e) => var_dump($e));
    }

    public static function handleException(\Throwable $e): void
    {
        http_response_code(500);

        if (Env::get('APP_DEBUG') === 'true') {
            self::renderStackTrace($e);
        } else {
            View::error('500');
        }
    }

    public static function handleError(int $severity, string $message, string $file, int $line): void
    {
        self::handleException(new \ErrorException($message, 0, $severity, $file, $line));
    }

    protected static function renderStackTrace(\Throwable $e): void
    {
        $file = $e->getFile();
        $line = $e->getLine();
        $preview = [];

        if (is_file($file)) {
            $lines = file($file);
            $start = max(0, $line - 10);
            $end = min(count($lines), $line + 10);

            for ($i = $start; $i < $end; $i++) {
                $preview[] = [
                    'line' => $i + 1,
                    'code' => rtrim($lines[$i]),
                    'highlight' => ($i + 1 === $line),
                ];
            }
        }

        View::render('core::Errors.stacktrace', [
            'exception' => $e,
            'class'     => get_class($e),
            'message'   => $e->getMessage(),
            'file'      => $file,
            'line'      => $line,
            'trace'     => $e->getTraceAsString(),
            'preview'   => $preview,
        ]);
    }
}
