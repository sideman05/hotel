<?php
header('Content-Type: application/json');

$mysqli = new mysqli("localhost", "root", "", "hotel_management"); // adjust DB
if ($mysqli->connect_errno) {
    echo json_encode(['success'=>false,'message'=>$mysqli->connect_error]);
    exit;
}

$action = $_REQUEST['action'] ?? '';

switch($action) {
    case 'create':
        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? '';
        $price = $_POST['price'] ?? 0;
        $capacity = $_POST['capacity'] ?? '';
        $status = $_POST['status'] ?? 'Available';
        $description = $_POST['description'] ?? '';
        $features = $_POST['features'] ?? '[]';
        
        // Handle file uploads
        $images = [];
        $uploadDir = __DIR__ . '/uploads/';
        $uploadDirRel = 'uploads/';
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

    $stmt = $mysqli->prepare("INSERT INTO rooms (name,type,price,capacity,status,description,features,images) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssisssss", $name, $type, $price, $capacity, $status, $description, $features, json_encode($images));
        if ($stmt->execute()) {
            echo json_encode(['success'=>true]);
        } else {
            echo json_encode(['success'=>false,'message'=>$stmt->error]);
        }
        break;

    case 'read':
        $res = $mysqli->query("SELECT * FROM rooms ORDER BY id DESC");
        $rooms = [];
        while($row = $res->fetch_assoc()) $rooms[] = $row;
        echo json_encode($rooms);
        break;

    case 'readOne':
        $id = $_GET['id'] ?? 0;
        $stmt = $mysqli->prepare("SELECT * FROM rooms WHERE id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        echo json_encode($res);
        break;

    case 'update':
        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? '';
        $price = $_POST['price'] ?? 0;
        $capacity = $_POST['capacity'] ?? '';
        $status = $_POST['status'] ?? 'Available';
        $description = $_POST['description'] ?? '';
        $features = $_POST['features'] ?? '[]';

        // Handle new images
        $images = [];
        $uploadDir = __DIR__ . '/uploads/';
        $uploadDirRel = 'uploads/';
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
            $imagesJSON = json_encode($images);
            $stmt = $mysqli->prepare("UPDATE rooms SET name=?, type=?, price=?, capacity=?, status=?, description=?, features=?, images=? WHERE id=?");
            $stmt->bind_param("ssisssssi",$name,$type,$price,$capacity,$status,$description,$features,$imagesJSON,$id);
        } else {
            $stmt = $mysqli->prepare("UPDATE rooms SET name=?, type=?, price=?, capacity=?, status=?, description=?, features=? WHERE id=?");
            $stmt->bind_param("ssissssi",$name,$type,$price,$capacity,$status,$description,$features,$id);
        }
        if($stmt->execute()){
            echo json_encode(['success'=>true]);
        } else {
            echo json_encode(['success'=>false,'message'=>$stmt->error]);
        }
        break;

    case 'delete':
        $id = $_POST['id'] ?? 0;
        $stmt = $mysqli->prepare("DELETE FROM rooms WHERE id=?");
        $stmt->bind_param("i",$id);
        if($stmt->execute()){
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
