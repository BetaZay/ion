<?php

use core\support\Env;

beforeEach(function () {
    // Clear env to simulate a fresh state
    $_ENV = [];
    $_SERVER = [];
});

test('loads .env values into $_ENV and $_SERVER', function () {
    $tempFile = tempnam(sys_get_temp_dir(), 'env');
    file_put_contents($tempFile, <<<ENV
APP_NAME=Ion
DEBUG=true
PORT=8080
ENV);

    Env::load($tempFile);

    expect($_ENV['APP_NAME'])->toBe('Ion')
        ->and($_ENV['DEBUG'])->toBe('true')
        ->and($_ENV['PORT'])->toBe('8080')
        ->and($_SERVER['APP_NAME'])->toBe('Ion')
        ->and($_SERVER['DEBUG'])->toBe('true')
        ->and($_SERVER['PORT'])->toBe('8080');

    unlink($tempFile);
});

test('handles quoted values', function () {
    $tempFile = tempnam(sys_get_temp_dir(), 'env');
    file_put_contents($tempFile, <<<ENV
NAME="Ion Framework"
PASSWORD='secret'
ENV);

    Env::load($tempFile);

    expect($_ENV['NAME'])->toBe('Ion Framework')
        ->and($_ENV['PASSWORD'])->toBe('secret');

    unlink($tempFile);
});

test('ignores comments and blank lines', function () {
    $tempFile = tempnam(sys_get_temp_dir(), 'env');
    file_put_contents($tempFile, <<<ENV
# This is a comment

FOO=bar

# Another comment
BAZ=buzz
ENV);

    Env::load($tempFile);

    expect($_ENV['FOO'])->toBe('bar')
        ->and($_ENV['BAZ'])->toBe('buzz');

    unlink($tempFile);
});

test('returns default value if key not found', function () {
    expect(Env::get('NON_EXISTENT', 'fallback'))->toBe('fallback')
        ->and(Env::get('NON_EXISTENT'))->toBeNull();
});
