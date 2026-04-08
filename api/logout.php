<?php
/**
 * ШиноСервис — Выход из системы
 * POST /api/logout.php
 */

require_once __DIR__ . '/helpers.php';

set_api_headers('POST, OPTIONS');
session_start_once();

if (request_method() !== 'POST') {
    respond_error('Метод не поддерживается', 405);
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

session_destroy();
respond(['message' => 'Вы вышли из системы']);
