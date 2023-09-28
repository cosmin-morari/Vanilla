<?php
require_once('common.php');

if ($_SERVER['REQUEST_METHOD'] === $_GET['idOrder']) {
    header('location:orders.php');
    exit;
}

$conn = connDataBase();
$idOrder = isset($_GET['idOrder']) && $_GET['idOrder'] ? $_GET['idOrder'] : '';
$idInOrderTable = [];
$querySelectIdOrders = "SELECT id FROM orders";
$idsOrders = $conn->query($querySelectIdOrders);

while ($row = $idsOrders->fetch_assoc()) {
    $idInOrderTable[] = $row['id'];
}

if (in_array($idOrder, $idInOrderTable)) {
    $querySelectAllOrder = "SELECT * FROM orders WHERE id IN(?)";
    $stmt = $conn->prepare($querySelectAllOrder);

    if ($stmt) {
        $stmt->bind_param('i', $idOrder);
    }

    $stmt->execute();
    $result = $stmt->get_result();
} else {
    header('location:orders.php');
    exit;
}

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
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <td><?= $row['customer_details']; ?></td>
                    <td><?= $row['purchased_products']; ?></td>
                    <td><?= translate('Pending'); ?></td>
                <?php endwhile; ?>
            </tbody>
            </thead>
        </table>
        <a href="orders.php"><?= translate('ViewOrders') ?></a>

    </div>
</body>

</html>