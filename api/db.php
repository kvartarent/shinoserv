<?php
/**
 * ШиноСервис — Подключение к базе данных
 *
 * Singleton-класс: создаёт одно PDO-соединение за весь запрос.
 * Использование: $pdo = DB::get();
 */

require_once __DIR__ . '/config.php';

class DB
{
    private static ?PDO $instance = null;

    /**
     * Возвращает PDO-соединение (создаёт при первом вызове).
     */
    public static function get(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                error_log('[DB] Connection failed: ' . $e->getMessage());
                http_response_code(500);
                echo json_encode(['ok' => false, 'error' => 'Ошибка подключения к базе данных']);
                exit;
            }
        }

        return self::$instance;
    }

    private function __construct() {}
    private function __clone() {}
}
