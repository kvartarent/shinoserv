<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../db.php';

set_api_headers('GET, OPTIONS');
session_start_once();
require_superadmin();

$db = DB::get();

$totalServices = (int)$db->query('SELECT COUNT(*) FROM services')->fetchColumn();
$activeServices = (int)$db->query('SELECT COUNT(*) FROM services WHERE is_active=1')->fetchColumn();
$totalOwners = (int)$db->query('SELECT COUNT(*) FROM owners WHERE role="owner"')->fetchColumn();
$totalBookings = (int)$db->query('SELECT COUNT(*) FROM bookings')->fetchColumn();
$todayBookings = (int)$db->query('SELECT COUNT(*) FROM bookings WHERE DATE(created_at)=CURTATE()')->fetchColumn();
$totalRevenue = (int)$db->query('SELECT COALESCE(SUM(total_price),0) FROM bookings WHERE status="completed"')->fetchColumn();

$top5 = $db->query("
    SELECT s.name, s.address, COUNT(b.id) AS bookings_count,
           COALESCE(SUM(b.total_price),0) AS revenue
    FROM services s
    LEFT JOIN bookings b ON b.service_id=s.id AND btatus='completed'
    GROUP BY s.id
    ORDER BY bookings_count DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

respond(compact('totalServices','activeServices','totalOwners','totalBookings','todayBookings','totalRevenue','top5'));
