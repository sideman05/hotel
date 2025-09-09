<?php
header('Content-Type: application/json');
require_once 'db.php';

// Total bookings
$totalBookings = $conn->query("SELECT COUNT(*) as cnt FROM bookings")->fetch_assoc()['cnt'];

// Revenue (sum of all bookings, fallback to room price if needed)
$revenue = 0;
$bookings = $conn->query("SELECT * FROM bookings ORDER BY id DESC");
$rooms = [];
$roomRes = $conn->query("SELECT * FROM rooms");
while ($r = $roomRes->fetch_assoc()) {
    $rooms[$r['type']] = $r;
}
$recentBookings = [];
$i = 0;
while ($b = $bookings->fetch_assoc()) {
    // Revenue calculation
    if (!empty($b['total_price'])) {
        $revenue += floatval($b['total_price']);
    } elseif (!empty($b['price'])) {
        $revenue += floatval($b['price']);
    } elseif (!empty($b['room_type']) && !empty($b['check_in']) && !empty($b['check_out'])) {
        $roomType = $b['room_type'];
        $roomPrice = isset($rooms[$roomType]) ? floatval($rooms[$roomType]['price']) : 0;
        $d1 = strtotime($b['check_in']);
        $d2 = strtotime($b['check_out']);
        $nights = max(1, round(($d2-$d1)/86400));
        $revenue += $roomPrice * $nights;
    }
    // Recent bookings (last 4)
    if ($i < 4) {
        $recentBookings[] = $b;
        $i++;
    }
}

// Occupancy rate
$totalRooms = $conn->query("SELECT COUNT(*) as cnt FROM rooms")->fetch_assoc()['cnt'];
$occupiedRooms = $conn->query("SELECT COUNT(*) as cnt FROM rooms WHERE status != 'Available'")->fetch_assoc()['cnt'];
$occupancyRate = $totalRooms ? round(($occupiedRooms/$totalRooms)*100) : 0;

// New guests (last 30 days)
$newGuests = $conn->query("SELECT COUNT(*) as cnt FROM guests WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetch_assoc()['cnt'];

// Output all stats
$response = [
    'total_bookings' => intval($totalBookings),
    'revenue' => floatval($revenue),
    'occupancy_rate' => intval($occupancyRate),
    'new_guests' => intval($newGuests),
    'recent_bookings' => $recentBookings
];
echo json_encode($response);
