<?php
require_once('common.php');
session_start();
checkAdmin();

if ($_SERVER['REQUEST_METHOD'] === $_GET['idOrder']) {
    header('location:orders.php');
    exit;
}

$conn = connDataBase();
$idOrder = isset($_GET['idOrder']) && $_GET['idOrder'] ? $_GET['idOrder'] : '';
$querySelectIdOrders = "SELECT id FROM orders";
$idsOrders = $conn->query($querySelectIdOrders);
$idInOrderTable = ($idsOrders) ? array_column($idsOrders->fetch_all(MYSQLI_ASSOC), 'id') : '';

if (in_array($idOrder, $idInOrderTable)) {
    $querySelectAllOrder = "SELECT 
                            *
                            FROM products_orders
                            JOIN orders ON products_orders.order_id = orders.id
                            WHERE order_id = ?";
    $stmt = $conn->prepare($querySelectAllOrder);

    if ($stmt) {
        $stmt->bind_param('i', $idOrder);
    }

    $stmt->execute();
} else {
    header('location:orders.php');
    exit;
}

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translate('Order'); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>
    <div class="content">
        <h1><?= translate('Order'); ?></h1>
        <table style="text-align: center;" border="1">
            <thead>
                <tr>
                    <th><?= translate('CheckoutInformation'); ?></th>
                    <th><?= translate('PurchasedProducts'); ?></th>
                    <th><?= translate('Status'); ?></th>
                </tr>
            <tbody>
                <td><?= $row['customer_details']; ?></td>
                <td><?= $row['purchased_products']; ?></td>
                <td><?= translate('Pending'); ?></td>
            </tbody>
            </thead>
        </table>
        <a href="orders.php"><?= translate('ViewOrders') ?></a>
    </div>
</body>

</html>