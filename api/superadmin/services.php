<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../db.php';

set_api_headers('GET, PUT, OPTIONS');
session_start_once();
require_superadmin();

$method = request_method();

if ($method === 'PUT') {
    $data = get_json_body();
    $id   = (int)($data['id'] ?? 0);
    $active = isset($data['is_active']) ? (int)(bool)$data['is_active'] : null;
    if (!id || $active === null) respond_error('Не указан id или is_active');
    DB::get()->prepare('UPDATE services SET is_active=? WHERE id=?')->execute([$active, $id]);
    respond(['id' => $id, 'is_active' => (bool)$active]);
}

$r = DB::get()->query("
    SELECT s.id, s.name, s.address, s.is_active, s.created_at,
           o.login AS owner_login,
           COUNT(b.id) AS bookings_count
    FROM services s
    JOIN owners o ON o.id = s.owner_id
    LEFT JOIN bookings b ON b.service_id = s.id
    GROUP BY s.id
    ORDER BY s.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
respond($r);
