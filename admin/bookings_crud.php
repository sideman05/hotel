<?php
header('Content-Type: application/json');
$mysqli = new mysqli("localhost", "root", "", "hotel_management");
if ($mysqli->connect_errno) {
    echo json_encode(['success'=>false,'message'=>$mysqli->connect_error]);
    exit;
}
$action = $_REQUEST['action'] ?? '';

switch($action) {
    case 'create':
        $room_id = $_POST['room_id'] ?? null;
        $room_type = $_POST['room_type'] ?? '';
        $guest_name = $_POST['guest_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $check_in = $_POST['check_in'] ?? '';
        $check_out = $_POST['check_out'] ?? '';
        $guests = $_POST['guests'] ?? '';
        $stmt = $mysqli->prepare("INSERT INTO bookings (room_id, room_type, guest_name, email, phone, check_in, check_out, guests) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $room_id, $room_type, $guest_name, $email, $phone, $check_in, $check_out, $guests);
        if ($stmt->execute()) {
            echo json_encode(['success'=>true]);
        } else {
            echo json_encode(['success'=>false,'message'=>$stmt->error]);
        }
        break;
    case 'read':
        $res = $mysqli->query("SELECT * FROM bookings ORDER BY id DESC");
        $bookings = [];
        while($row = $res->fetch_assoc()) $bookings[] = $row;
        echo json_encode($bookings);
        break;
    case 'update_status':
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? '';
        $stmt = $mysqli->prepare("UPDATE bookings SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $id);
        if ($stmt->execute()) {
            echo json_encode(['success'=>true]);
        } else {
            echo json_encode(['success'=>false,'message'=>$stmt->error]);
        }
        break;
    default:
        echo json_encode(['success'=>false,'message'=>'Invalid action']);
        break;
}
?>
