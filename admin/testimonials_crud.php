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
        $name = $_POST['name'] ?? '';
        $room_type = $_POST['room_type'] ?? '';
        $stay_date = $_POST['stay_date'] ?? '';
        $rating = $_POST['rating'] ?? 0;
        $text = $_POST['text'] ?? '';
        $status = $_POST['status'] ?? 'pending';
        $testimonial_date = $_POST['testimonial_date'] ?? date('Y-m-d');
        $featured = isset($_POST['featured']) ? 1 : 0;
        $avatar = '';
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        if (!empty($_FILES['avatar']['name'])) {
            $tmpName = $_FILES['avatar']['tmp_name'];
            $fileType = mime_content_type($tmpName);
            if (strpos($fileType, 'image/') !== 0) {
                echo json_encode(['success'=>false,'message'=>'Only image files are allowed.']);
                exit;
            }
            $newName = time().'_'.$_FILES['avatar']['name'];
            $targetPath = $uploadDir . $newName;
            if (!move_uploaded_file($tmpName, $targetPath)) {
                echo json_encode(['success'=>false,'message'=>'Failed to upload avatar.']);
                exit;
            }
            $avatar = $newName;
        }
        $stmt = $mysqli->prepare("INSERT INTO testimonials (name, room_type, stay_date, rating, text, status, testimonial_date, featured, avatar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            echo json_encode(['success'=>false,'message'=>'DB Prepare failed: '.$mysqli->error]);
            exit;
        }
        $stmt->bind_param("sssisssis", $name, $room_type, $stay_date, $rating, $text, $status, $testimonial_date, $featured, $avatar);
        if (!$stmt->execute()) {
            echo json_encode(['success'=>false,'message'=>'DB Execute failed: '.$stmt->error]);
            exit;
        }
        echo json_encode(['success'=>true]);
        break;
    case 'read':
        $res = $mysqli->query("SELECT * FROM testimonials ORDER BY id DESC");
        $testimonials = [];
        while($row = $res->fetch_assoc()) $testimonials[] = $row;
        echo json_encode($testimonials);
        break;
    case 'update':
        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $room_type = $_POST['room_type'] ?? '';
        $stay_date = $_POST['stay_date'] ?? '';
        $rating = $_POST['rating'] ?? 0;
        $text = $_POST['text'] ?? '';
        $status = $_POST['status'] ?? 'pending';
        $testimonial_date = $_POST['testimonial_date'] ?? date('Y-m-d');
        $featured = isset($_POST['featured']) ? 1 : 0;
        $avatar = $_POST['current_avatar'] ?? '';
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        if (!empty($_FILES['avatar']['name'])) {
            $tmpName = $_FILES['avatar']['tmp_name'];
            $fileType = mime_content_type($tmpName);
            if (strpos($fileType, 'image/') !== 0) {
                echo json_encode(['success'=>false,'message'=>'Only image files are allowed.']);
                exit;
            }
            $newName = time().'_'.$_FILES['avatar']['name'];
            $targetPath = $uploadDir . $newName;
            if (!move_uploaded_file($tmpName, $targetPath)) {
                echo json_encode(['success'=>false,'message'=>'Failed to upload avatar.']);
                exit;
            }
            $avatar = $newName;
        }
        $stmt = $mysqli->prepare("UPDATE testimonials SET name=?, room_type=?, stay_date=?, rating=?, text=?, status=?, testimonial_date=?, featured=?, avatar=? WHERE id=?");
        $stmt->bind_param("sssisssisi", $name, $room_type, $stay_date, $rating, $text, $status, $testimonial_date, $featured, $avatar, $id);
        $stmt->execute();
        echo json_encode(['success'=>true]);
        break;
    case 'delete':
        $id = $_POST['id'] ?? 0;
        $stmt = $mysqli->prepare("SELECT avatar FROM testimonials WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if ($res && isset($res['avatar']) && $res['avatar']) {
            $file = __DIR__ . '/uploads/' . $res['avatar'];
            if (file_exists($file)) unlink($file);
        }
        $stmt = $mysqli->prepare("DELETE FROM testimonials WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(['success'=>true]);
        break;
    default:
        echo json_encode(['success'=>false,'message'=>'Invalid action']);
        break;
}
?>
