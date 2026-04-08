<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../db.php';

set_api_headers('GET, OPTIONS');
session_start_once();
require_superadmin();

$limit = min(100, (int)($_GET['limit'] ?? 50));
$offset = max(0, (int)($_GET['offset'] ?? 0));

$bookings = DB::get()->query("
    SELECT b.id, b.date, b.time, b.status, b.total_price,
           b.client_name, b.client_phone, b.created_at,
           s.name AS service_name
    FROM bookings b
    JOIN services s ON s.id = b.service_id
    ORDER BY b.created_at DESC
    LIMIT $limit OFFSET $offset
")->fetchAll(PDO::FETCH_ASSOC);

respond($bookings);
