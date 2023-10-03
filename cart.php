<?php
session_start();
require_once('common.php');

//PHP MAILER
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';

//delete products
if (!empty($_POST['id'])) {
    $idProduct = $_POST['id'];
    $index = array_search($idProduct, $_SESSION['idProducts']);
    unset($_SESSION['idProducts'][$index]);
}

// data processing
$conn = connDataBase();
$idsFromSession = [];
if (isset($_SESSION['idProducts'])) {
    foreach ($_SESSION['idProducts'] as $ids) {
        $idsFromSession[] = $ids;
    }
}

$hasCartItems = isset($_SESSION['idProducts']) && !empty($_SESSION['idProducts']) ? $_SESSION['idProducts'] : '';
$letterTotal = str_repeat('i', count($idsFromSession));
$totalQuestionMark = implode(', ', array_fill(0, count($idsFromSession), '?'));

if ($hasCartItems) {
    $querySelectProducts = "SELECT * FROM products WHERE id IN ($totalQuestionMark)";
    $stmt = $conn->prepare($querySelectProducts);
    if ($stmt && $hasCartItems) {
        $stmt->bind_param($letterTotal, ...$idsFromSession);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    


    $errors = [];

    if (isset($_POST['submit']) && $_POST['submit']) {

        $name = $_POST['name'] ?? '';
        $contactDetails = $_POST['contactDetails'] ?? '';
        $comments = $_POST['comments'] ?? '';

        if (!$name || !$contactDetails || !$comments) {
            $errors['message'] = translate('Required');
        } else {
            try {
                $phpmailer = new PHPMailer();
                $phpmailer->isSMTP();
                $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
                $phpmailer->SMTPAuth = true;
                $phpmailer->Port = 2525;
                $phpmailer->Username = 'de1f82dd0e1204';
                $phpmailer->Password = 'fc648a2f712110';

                //Recipients
                $phpmailer->setFrom($contactDetails, $name);
                $phpmailer->addAddress(MAIL, 'Administrator');

                //Content
                $tomail = true;
                if ($tomail) {
                    ob_start();
                    include 'cartTemplate.php';
                    $cartMail = ob_get_clean();
                    $confirmOrder = true;
                    $phpmailer->isHTML(true);
                    $phpmailer->Subject = 'The order!';
                    $phpmailer->Body = $cartMail;
                    $phpmailer->send();
                }

                if ($confirmOrder) {
                    $tomail = false;
                    $phpmailer->ClearAllRecipients();
                    $phpmailer->ClearAddresses();
                    mysqli_data_seek($result, 0);
                    ob_start();
                    include 'cartTemplate.php';
                    $cartMail = ob_get_clean();
                    $phpmailer->isHTML(true);
                    $phpmailer->setFrom(MAIL, 'Administrator');
                    $phpmailer->addAddress($contactDetails, $name);
                    $phpmailer->Subject = 'This your order!';
                    $phpmailer->Body = $cartMail;
                    $phpmailer->send();
                }


                $purchasedProducts = array();
                mysqli_data_seek($result, 0);

                while ($rows = $result->fetch_assoc()) {
                    $purchasedProducts[] = $rows['title'];
                }
                $productsInOrder = implode(', ', $purchasedProducts);

                $querySumPrice = "SELECT SUM(price) FROM products WHERE id IN ($totalQuestionMark)";
                $stmt = $conn->prepare($querySumPrice);

                if ($stmt && $hasCartItems) {
                    $stmt->bind_param($letterTotal, ...$idsFromSession);
                }

                $stmt->execute();

                $querySum = $stmt->get_result();
                $rows = $querySum->fetch_assoc();
                $totalPriceOrder  = $rows['SUM(price)'];

                $date = date('Y-m-d h-i-s');
                $customerDetails = $name . ', ' . $contactDetails . ', ' . $comments;

                // INSERT ORDERS
                if ($date && $customerDetails && $productsInOrder && $totalPriceOrder) {
                    $insertQuery = 'INSERT INTO orders (date, customer_details, purchased_products, total_price) VALUES (?, ?, ?, ?)';
                    $stmt = $conn->prepare($insertQuery);

                    if ($stmt) {
                        $stmt->bind_param('sssi', $date, $customerDetails, $productsInOrder, $totalPriceOrder);
                    }

                    $stmt->execute();
                    $idForLastOrder = $stmt->insert_id;

                    mysqli_data_seek($result, 0);

                    while ($row = $result->fetch_assoc()) {
                        $insertPivot = 'INSERT INTO products_orders(product_id, order_id, price) VALUES (?, ?, ?)';
                        $stmt = $conn->prepare($insertPivot);

                        if ($stmt) {
                            $stmt->bind_param('iii', $row['id'], $idForLastOrder, $row['price']);
                        }

                        $stmt->execute();
                    }
                }

                array_splice($_SESSION['idProducts'], 0);
                header('location:index.php');
                exit;
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
            }
        }
    }
    $conn->close();
}

$tomail = false;
$confirmOrder = false;

include 'cartTemplate.php';
