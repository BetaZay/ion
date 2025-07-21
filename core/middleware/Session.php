<?php

namespace core\middleware;

use app\models\Session as SessionModel;
use core\bootstrap\App;

class Session implements Middleware
{
    public function handle(callable $next): void
    {
        $uuid = App::name() . hash('sha256',random_bytes(24));
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

        $id = $_COOKIE['SESSION_ID'] ?? null;
        $token = $_COOKIE['XSRF-TOKEN'] ?? null;

        if ($id && $id === $token) {
            $session = SessionModel::find($id);
            if ($session) {
                $session->update([
                    'last_activity' => time(),
                ]);

                echo "Session Resumed!\n";
                $next();
                return;
            }
        }

        SessionModel::findOrCreate($uuid, [
            'ip_address'    => $ip,
            'user_agent'    => $ua,
            'payload'       => '{}',
            'last_activity' => time(),
        ]);

        setcookie(strtoupper(App::name()), $uuid, [
            'expires' => time() + 604800,
            'path' => '/',
            'httponly' => true,
            'secure' => isset($_SERVER['HTTPS']),
            'samesite' => 'Lax',
        ]);

        setcookie('XSRF-TOKEN', $uuid, [
            'expires' => time() + 604800,
            'path' => '/',
            'httponly' => false,
            'secure' => isset($_SERVER['HTTPS']),
            'samesite' => 'Lax',
        ]);

        $next();
    }
}
