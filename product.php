<?php
session_start();
require_once('common.php');
checkAdmin();

$conn = connDataBase();

$editId = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '';

if (!isset($_GET['id'])) {
    $destination = 'add';
} else {
    $destination = 'edit';
}

if (isset($_POST['save'])  && isset($_FILES['image'])) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';

    $imageName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];
    $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png', 'jfif', 'jpe', 'jif', 'jfi'];
    $newImageName = uniqid("IMG-", true) . '.' . $imageExtension;
    $imagePath = 'assets/photos/' . $newImageName;
    move_uploaded_file($tmpName, $imagePath);

    $errors = [];

    if (!isset($_GET['id'])) {
        if ($title && $description && $price) {
            if (in_array($imageExtension, $allowedExtensions)) {
                $insertQuery = "INSERT INTO products (title, description, price, imageSource) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                if ($stmt) {
                    $stmt->bind_param('ssis', $title, $description, $price, $newImageName);
                }
                $stmt->execute();
                header('location:products.php');
                exit;
            } else {
                $errors['message'] = translate('ImageErr');
            }
        } else {
            $errors['message'] = translate('Required');
        }
    }

    if (isset($_GET['id']) && $_GET['id']) {
        if ($title || $description || $price) {
            if (in_array($imageExtension, $allowedExtensions)) {
                $updateQuery = "UPDATE products SET title = ?, description = ?, price = ?, imageSource = ? WHERE id = ?";
                $stmt = $conn->prepare($updateQuery);

                if ($stmt) {
                    $stmt->bind_param('ssisi', $title, $description, $price, $newImageName, $editId);
                }

                $stmt->execute();
                header('location:products.php');
                exit;
            } elseif (isset($_FILES['image']) && !in_array($imageExtension, $allowedExtensions)) {
                $errors['message'] = translate('ImageErr');
            }
        }
    }
}

$selectAllProducts = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($selectAllProducts);

if ($stmt) {
    $stmt->bind_param('i', $editId);
}

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translate('Product'); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>
    <form method="POST" enctype="multipart/form-data">
        <?php
        include 'productTemplate.php';
        ?>
    </form>
</body>

</html>