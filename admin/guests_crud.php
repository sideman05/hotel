<?php
// guests_crud.php
header('Content-Type: application/json');
require_once 'db.php'; // correct path for db.php

$action = $_REQUEST['action'] ?? '';

function respond($data) {
    echo json_encode($data);
    exit;
}

if ($action === 'read') {
    $guests = [];
    $sql = "SELECT * FROM guests ORDER BY id DESC";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $guests[] = $row;
    }
    respond($guests);
}

if ($action === 'create') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $country = $_POST['country'] ?? '';
    $id_type = $_POST['id_type'] ?? '';
    $id_number = $_POST['id_number'] ?? '';
    $address = $_POST['address'] ?? '';
    $loyalty_tier = $_POST['loyalty_tier'] ?? '';
    $preferences = $_POST['preferences'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $sql = "INSERT INTO guests (first_name, last_name, email, phone, country, id_type, id_number, address, loyalty_tier, preferences, notes) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssssss', $first_name, $last_name, $email, $phone, $country, $id_type, $id_number, $address, $loyalty_tier, $preferences, $notes);
    $ok = $stmt->execute();
    respond(['success' => $ok, 'id' => $conn->insert_id]);
}

if ($action === 'update') {
    $id = $_POST['id'] ?? 0;
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $country = $_POST['country'] ?? '';
    $id_type = $_POST['id_type'] ?? '';
    $id_number = $_POST['id_number'] ?? '';
    $address = $_POST['address'] ?? '';
    $loyalty_tier = $_POST['loyalty_tier'] ?? '';
    $preferences = $_POST['preferences'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $sql = "UPDATE guests SET first_name=?, last_name=?, email=?, phone=?, country=?, id_type=?, id_number=?, address=?, loyalty_tier=?, preferences=?, notes=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssssssi', $first_name, $last_name, $email, $phone, $country, $id_type, $id_number, $address, $loyalty_tier, $preferences, $notes, $id);
    $ok = $stmt->execute();
    respond(['success' => $ok]);
}

if ($action === 'delete') {
    $id = $_POST['id'] ?? 0;
    $sql = "DELETE FROM guests WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $ok = $stmt->execute();
    respond(['success' => $ok]);
}

respond(['error' => 'Invalid action']);
