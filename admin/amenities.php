<?php
// amenities_crud.php

$host = "localhost";
$user = "root";
$pass = "";
$db = "hotel_management"; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// CREATE
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $icon = $_POST['icon'];
    $status = $_POST['status'];
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = 'uploads/';
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $image = $uploadDir . $imageName;
        $targetPath = __DIR__ . '/' . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    }
    $stmt = $conn->prepare("INSERT INTO amenities (name, category, description, icon, image, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $category, $description, $icon, $image, $status);
    $stmt->execute();
    echo "Amenity added successfully";
    exit;
}

// READ (all)
if (isset($_GET['action']) && $_GET['action'] == 'read') {
    $result = $conn->query("SELECT * FROM amenities ORDER BY id DESC");
    $amenities = [];
    while ($row = $result->fetch_assoc()) {
        $amenities[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($amenities);
    exit;
}

// UPDATE
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $icon = $_POST['icon'];
    $status = $_POST['status'];
    $image = '';
    $update_image = false;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = 'uploads/';
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $image = $uploadDir . $imageName;
        $targetPath = __DIR__ . '/' . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        $update_image = true;
    } else {
        // Get current image from DB
        $stmt_img = $conn->prepare("SELECT image FROM amenities WHERE id=?");
        $stmt_img->bind_param("i", $id);
        $stmt_img->execute();
        $stmt_img->bind_result($current_image);
        $stmt_img->fetch();
        $stmt_img->close();
        $image = $current_image;
    }
    $stmt = $conn->prepare("UPDATE amenities SET name=?, category=?, description=?, icon=?, image=?, status=? WHERE id=?");
    $stmt->bind_param("ssssssi", $name, $category, $description, $icon, $image, $status, $id);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Amenity updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Update failed", "error" => $stmt->error]);
    }
    exit;
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Amenity updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Update failed", "error" => $stmt->error]);
    }
    exit;
}

// DELETE
if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM amenities WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "Amenity deleted successfully";
    exit;
}

$conn->close();
?>