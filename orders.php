<?php
require_once('common.php');
session_start();
$conn = connDataBase();

$queryOrders = "SELECT * FROM orders";
$results = $conn->query($queryOrders);

if (isset($_POST['viewOrder']) && $_POST['viewOrder']) {
    if (isset($_POST['idOrder']) && $_POST['idOrder']) {
        header('location: order.php?idOrder=' . $_POST['idOrder']);
        exit;
    }
}

//PHP MAILER

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';

if (isset($_POST['confirmOrder']) && $_POST['confirmOrder']) {
    if (isset($_POST['idOrder']) && $_POST['idOrder']) {
        $id = $_POST['idOrder'];
        if (isset($_POST['email']) && $_POST['email']) {
            $email = $_POST['email'];
            $query = "SELECT * FROM orders WHERE id IN (?)";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param('s', $id);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            include 'confirmEmail.php';
            $confirmEmail = ob_get_clean();

            try {
                $phpmailer = new PHPMailer();
                $phpmailer->isSMTP();
                $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
                $phpmailer->SMTPAuth = true;
                $phpmailer->Port = 2525;
                $phpmailer->Username = '23472fefac2901';
                $phpmailer->Password = 'a2190e69e3bc07';

                //Recipients
                $phpmailer->setFrom($email, 'Client');
                $phpmailer->addAddress(MAIL, 'Admin');

                //Content
                $phpmailer->isHTML(true);
                $phpmailer->Subject = 'Your order!';
                $phpmailer->Body = $confirmEmail;
                $phpmailer->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
            }
        }
    }
}

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
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['date'] ?></td>
                            <td><?= $row['customer_details'] ?></td>
                            <td><?= $row['purchased_products'] ?></td>
                            <td><?= $row['total_price'] ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="idOrder" value="<?= $row['id'] ?>">
                                    <input type="submit" name="viewOrder" value="<?= translate('Order') ?>">
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p style="text-align: center; color:red;font-weight:bolder"><?= translate('OrdersEmpty'); ?></p>
        <?php endif ?>
        <a href="index.php"><?= translate('GoToIndex') ?></a>
    </div>
</body>

</html>