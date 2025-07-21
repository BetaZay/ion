<?php

use core\support\Env;
use core\support\ErrorHandler;

// Create View test double in the correct namespace before any tests
beforeAll(function () {
    // Redefine View only if it's not already declared in tests
    if (!class_exists(\core\pulse\View::class, false)) {
        eval('
            namespace core\support;
            class View {
                public static $calls = [];
                public static function error($view) {
                    self::$calls[] = ["error", $view];
                }
                public static function render($view, $data = []) {
                    self::$calls[] = ["render", $view, $data];
                }
            }
        ');
    }
});

// Reset state before each test
beforeEach(function () {
    $_ENV = $_SERVER = [];
    \core\pulse\View::$calls = [];
});

// Test: Debug mode = show stacktrace
test('handleException renders stacktrace when APP_DEBUG is true', function () {
    Env::load(__DIR__ . '/__fixtures__/.env.debug');

    ob_start();
    ErrorHandler::handleException(new Exception('Debug Test'));
    ob_end_clean();

    $calls = \core\pulse\View::$calls;
    expect($calls)->toHaveCount(1)
        ->and($calls[0][0])->toBe('render')
        ->and($calls[0][1])->toBe('core::Errors.stacktrace')
        ->and($calls[0][2]['message'])->toBe('Debug Test');
});

// Test: Prod mode = show error page
test('handleException renders error page when APP_DEBUG is false', function () {
    Env::load(__DIR__ . '/__fixtures__/.env.prod');

    ob_start();
    ErrorHandler::handleException(new Exception('Prod Test'));
    ob_end_clean();

    $calls = \core\pulse\View::$calls;
    expect($calls)->toHaveCount(1)
        ->and($calls[0][0])->toBe('error')
        ->and($calls[0][1])->toBe('500');
});

// Test: handleError forwards as exception
test('handleError converts to exception', function () {
    Env::load(__DIR__ . '/__fixtures__/.env.debug');

    ob_start();
    ErrorHandler::handleError(E_USER_NOTICE, 'Custom error', __FILE__, __LINE__);
    ob_end_clean();

    $calls = \core\pulse\View::$calls;
    expect($calls[0][2]['message'])->toBe('Custom error');
});
