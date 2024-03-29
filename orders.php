<?php
require_once('common.php');
session_start();

checkAdmin();
$conn = connDataBase();

$queryOrders = 'SELECT
                order_id,
                date,
                customer_details,
                purchased_products,
                SUM(products_orders.price) as total_price
                FROM products_orders
                JOIN orders ON orders.id = products_orders.order_id
                GROUP BY orders.id
                ';
$results = $conn->query($queryOrders);

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title><?= translate('Orders'); ?></title>
</head>

<body>
    <div class="content">
        <h1 style="text-align: center;"><?= translate('Orders'); ?></h1>
        <?php if ($results->num_rows) : ?>
            <table style="text-align: center;" border="1">
                <thead>
                    <tr>
                        <th><?= translate('Id'); ?></th>
                        <th><?= translate('Date'); ?></th>
                        <th><?= translate('CustomerDetails'); ?></th>
                        <th><?= translate('PurchasedProducts'); ?></th>
                        <th><?= translate('TotalPrice'); ?></th>
                        <th><?= translate('Action'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $results->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $row['order_id'] ?></td>
                            <td><?= $row['date'] ?></td>
                            <td><?= $row['customer_details'] ?></td>
                            <td><?= $row['purchased_products'] ?></td>
                            <td><?= $row['total_price'] ?></td>
                            <td>
                                <a href="order.php?idOrder=<?= $row['order_id']; ?>"><?= translate('Order'); ?></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p style="text-align: center; color:red;font-weight:bolder"><?= translate('OrdersEmpty'); ?></p>
        <?php endif; ?>
        <a href="index.php"><?= translate('GoToIndex') ?></a>
    </div>
</body>

</html>