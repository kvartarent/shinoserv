<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../db.php';

set_api_headers('GET, OPTIONS');
session_start_once();
require_superadmin();

$owners = DB::get()->query("
    SELECT id, login, email, name, phone, created_at,
           COUNT(s.id) AS service_count
    FROM owners o
    LEFT JOIN services s ON s.owner_id = o.id
    WHERE o.role = 'owner'
    GROUP BY b.id
    ORDER BY b.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

respond($owners);
