<?php
header('Content-Type: application/json');

$mysqli = new mysqli("localhost", "root", "", "hotel_management");
if ($mysqli->connect_errno) {
    echo json_encode(['success'=>false,'message'=>$mysqli->connect_error]);
    exit;
}

$action = $_REQUEST['action'] ?? '';

switch($action) {
    case 'upload':
        $category = $_POST['category'] ?? 'rooms';
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $featured = isset($_POST['featured']) ? 1 : 0;
        $images = [];
        $uploadDir = __DIR__ . '/gallery_uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['name'] as $key => $nameFile) {
                $tmpName = $_FILES['images']['tmp_name'][$key];
                $fileType = mime_content_type($tmpName);
                if (strpos($fileType, 'image/') !== 0) {
                    echo json_encode(['success'=>false,'message'=>'Only image files are allowed.']);
                    exit;
                }
                $newName = time().'_'.$nameFile;
                $targetPath = $uploadDir . $newName;
                if (!move_uploaded_file($tmpName, $targetPath)) {
                    $err = error_get_last();
                    echo json_encode([
                        'success'=>false,
                        'message'=>'Failed to upload image: '.$nameFile,
                        'target'=>$targetPath,
                        'is_writable'=>is_writable($uploadDir),
                        'error'=>$err
                    ]);
                    exit;
                }
                $images[] = $newName;
            }
        }
        foreach ($images as $img) {
            $stmt = $mysqli->prepare("INSERT INTO gallery (filename, category, title, description, featured, uploaded_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssssi", $img, $category, $title, $description, $featured);
            $stmt->execute();
        }
        echo json_encode(['success'=>true]);
        break;
    case 'list':
        $res = $mysqli->query("SELECT * FROM gallery ORDER BY id DESC");
        $images = [];
        while($row = $res->fetch_assoc()) $images[] = $row;
        echo json_encode($images);
        break;
    case 'delete':
        $id = $_POST['id'] ?? 0;
        $stmt = $mysqli->prepare("SELECT filename FROM gallery WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if ($res && isset($res['filename'])) {
            $file = __DIR__ . '/gallery_uploads/' . $res['filename'];
            if (file_exists($file)) unlink($file);
        }
        $stmt = $mysqli->prepare("DELETE FROM gallery WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(['success'=>true]);
        break;
    default:
        echo json_encode(['success'=>false,'message'=>'Invalid action']);
        break;
}
?>
