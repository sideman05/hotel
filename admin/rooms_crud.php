<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "hotel_management");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed: " . $conn->connect_error]);
    exit;
}

/**
 * Helper function to handle file uploads
 */
function uploadImages($files) {
    $uploaded_files = [];
    if (!empty($files['name'][0])) {
        foreach ($files['name'] as $key => $filename) {
            $target = '../uploads/' . time() . '_' . basename($filename);
            if (move_uploaded_file($files['tmp_name'][$key], $target)) {
                $uploaded_files[] = basename($target);
            }
        }
    }
    return $uploaded_files;
}

/**
 * READ ALL
 */
if (isset($_GET['action']) && $_GET['action'] == 'read') {
    $result = $conn->query("SELECT * FROM rooms ORDER BY id DESC");
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $row['features'] = $row['features'] ?: '[]';
        $row['images'] = $row['images'] ?: '[]';
        $rooms[] = $row;
    }
    echo json_encode($rooms);
    exit;
}

/**
 * READ ONE
 */
if (isset($_GET['action']) && $_GET['action'] == 'readOne' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM rooms WHERE id=$id");
    $room = $result->fetch_assoc();
    if ($room) {
        $room['features'] = $room['features'] ?: '[]';
        $room['images'] = $room['images'] ?: '[]';
    }
    echo json_encode($room);
    exit;
}

/**
 * CREATE
 */
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    $name = $conn->real_escape_string($_POST['name']);
    $type = $conn->real_escape_string($_POST['type']);
    $price = floatval($_POST['price']);
    $capacity = $conn->real_escape_string($_POST['capacity']);
    $status = $conn->real_escape_string($_POST['status']);
    $description = $conn->real_escape_string($_POST['description']);
    $features = $_POST['features'] ?? '[]';

    $uploaded_files = uploadImages($_FILES['images']);
    $images = json_encode($uploaded_files);

    $sql = "INSERT INTO rooms (name, type, price, capacity, status, description, features, images)
            VALUES ('$name', '$type', $price, '$capacity', '$status', '$description', '$features', '$images')";
    if ($conn->query($sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => $conn->error]);
    }
    exit;
}

/**
 * UPDATE
 */
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $type = $conn->real_escape_string($_POST['type']);
    $price = floatval($_POST['price']);
    $capacity = $conn->real_escape_string($_POST['capacity']);
    $status = $conn->real_escape_string($_POST['status']);
    $description = $conn->real_escape_string($_POST['description']);
    $features = $_POST['features'] ?? '[]';

    // If new images uploaded, replace
    $uploaded_files = uploadImages($_FILES['images']);
    if (!empty($uploaded_files)) {
        $images = json_encode($uploaded_files);
        $sql = "UPDATE rooms SET 
                    name='$name', type='$type', price=$price, capacity='$capacity', 
                    status='$status', description='$description', features='$features', images='$images'
                WHERE id=$id";
    } else {
        $sql = "UPDATE rooms SET 
                    name='$name', type='$type', price=$price, capacity='$capacity', 
                    status='$status', description='$description', features='$features'
                WHERE id=$id";
    }

    if ($conn->query($sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => $conn->error]);
    }
    exit;
}

/**
 * DELETE
 */
if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = intval($_POST['id']);
    $conn->query("DELETE FROM rooms WHERE id=$id");
    echo json_encode(["success" => true]);
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid request"]);
