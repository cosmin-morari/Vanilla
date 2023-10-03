<?php
require_once('common.php');
session_start();

$idsFromSession = [];

if (isset($_SESSION['idProducts'])) {
    foreach ($_SESSION['idProducts'] as $ids) {
        $idsFromSession[] = $ids;
    }
}

$conn = connDataBase();
$hasCartItems = isset($_SESSION['idProducts']) && $_SESSION['idProducts'];
$letterTotal = str_repeat('i', count($idsFromSession));
$totalQuestionMark = implode(', ', array_fill(0, count($idsFromSession), '?'));
$selectAllProducts = $hasCartItems ? "SELECT * FROM products WHERE id NOT IN ($totalQuestionMark)" : 'SELECT * FROM products';
$stmt = $conn->prepare($selectAllProducts);

if ($stmt && $hasCartItems) {
    $stmt->bind_param($letterTotal, ...$idsFromSession);
}

$stmt->execute();
$result = $stmt->get_result();
$conn->close();

if (!empty($_POST['id'])) {
    $idProduct = $_POST['id'];
    if (empty($_SESSION['idProducts'])) {
        $_SESSION['idProducts'] = array();
    }
    if (!in_array($idProduct, $_SESSION['idProducts'])) {
        array_push($_SESSION['idProducts'], $idProduct);
        header('Location:index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translate('Index'); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <div class="container">
        <h1><?= translate('Index'); ?></h1>
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
                    <div class="addToCart">
                        <button type="submit" class="addToCartBtn"><?= translate('Add'); ?></button>
                    </div>
                </form>
            </div>
        <?php endwhile ?>
        <div class="links">
        <a href="cart.php"><?= translate('GoToCart') ?></a>
        <a href="orders.php"><?= translate('ViewOrders') ?></a>
        </div>
    </div>

</body>

</html>

<?php
