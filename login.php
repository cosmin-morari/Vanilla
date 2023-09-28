<?php
session_start();
require_once('common.php');

if (isset($_POST['submit']) && $_POST['submit']) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === ADMIN && $password === PASSWORDADMIN) {
        $_SESSION['admin'] = true;
        header('location:products.php');
        exit;
    } elseif (!$username || !$password) {
        $errors = [];
        $errors['message'] = translate('Required');
        $_SESSION['admin'] = false;
    } elseif ($username !== ADMIN || $password !== PASSWORDADMIN) {
        $errors = [];
        $errors['message'] = translate('WrongData');
        $_SESSION['admin'] = false;
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
    <form method="POST">
        <div class="container">
            <h3><?= translate('Login'); ?></h3>
            <input type="text" name="username" placeholder="Username">
            <br>
            <br>
            <input type="password" name="password" placeholder="Password">
            <br>
            <br>
            <input type="submit" name="submit" value="<?= translate('BtnLogin'); ?>">
            <?php if (isset($errors)) : ?>
                <p style="color:red;"><?= $errors['message']; ?></p>
            <?php endif; ?>
        </div>
    </form>
</body>

</html>