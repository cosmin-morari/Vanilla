<?php
session_start();
require_once('common.php');

if (isset($_POST['submit']) && $_POST['submit']) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $errors = [];
        $errors['message'] = translate('Required');
        $_SESSION['admin'] = false;
    } elseif ($username === ADMIN && $password === PASSWORDADMIN) {
        $_SESSION['admin'] = true;
        header('location:products.php');
        exit;
    } else {
        $errors = [];
        $errors['message'] = translate('Invalid');
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translate('Login'); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php if (!$_SESSION['admin']) : ?>
        <form method="POST">
            <div class="container">
                <h3><?= translate('Login'); ?></h3>
                <input type="text" name="username" placeholder="<?= translate('Username'); ?>">
                <br>
                <br>
                <input type="password" name="password" placeholder="<?= translate('Password'); ?>">
                <br>
                <br>
                <input type="submit" name="submit" value="<?= translate('BtnLogin'); ?>">
                <?php if (isset($errors)) : ?>
                    <p style="color:red;"><?= $errors['message']; ?></p>
                <?php endif; ?>
            </div>
        </form>
        <?php else: ?>
            <h1><?= translate('IsConnected'); ?></h1>
            <a href="orders.php"><?= translate('ViewOrders') ?></a>
    <?php endif; ?>
</body>

</html>