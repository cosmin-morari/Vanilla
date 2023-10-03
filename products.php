<?php
session_start();
require_once('common.php');
checkAdmin();

$conn = connDataBase();

if (isset($_POST['delete']) && $_POST['delete']) {
    $idProduct = $_POST['id'];
    $deleteProduct = 'DELETE FROM products WHERE id IN (?)';
    $stmt = $conn->prepare($deleteProduct);
    if ($stmt && $idProduct) {
        $stmt->bind_param('i', $idProduct);
    }
    $stmt->execute();
    header('location: products.php');
    exit;
}

if (isset($_POST['logout']) && $_POST['logout']) {
    $_SESSION['admin'] = false;
    header('location:login.php');
    exit;
}

$selectAllProducts = 'SELECT * FROM products';
$result = $conn->query($selectAllProducts);
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translate('Products') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <div class="container">
        <h1><?= translate('Products') ?></h1>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="content">
                <form method="POST">
                    <div class="img">
                        <img src="assets/photos/<?= $row['imageSource'] ?>" alt="img">
                    </div>
                    <div class="details">
                        <p><?= translate('Title'); ?>: <?= $row['title']; ?></p>
                        <p><?= translate('Description'); ?>: <?= $row['description']; ?></p>
                        <p><?= translate('Price'); ?>: <?= $row['price']; ?></p>
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                    </div>
                    <div class="buttons">
                        <a href="product.php?id=<?= $row['id']; ?>"> <?= translate('Edit'); ?></a>
                        <input type="submit" class="deleteProducts" name="delete" value="<?= translate('Delete'); ?>">
                    </div>
                </form>
            </div>
        <?php endwhile ?>
        <div class="buttons">
            <form method="POST">
                <a href="product.php"><?= translate('AddProduct'); ?></a>
                <input type="submit" class="logout" name="logout" value="<?= translate('Logout'); ?>">
            </form>
        </div>
    </div>

</body>

</html>