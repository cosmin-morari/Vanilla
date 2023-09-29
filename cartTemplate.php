<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (isset($confirmOrder) && !$confirmOrder) : ?>
        <title> <?= translate('Cart'); ?></title>
    <?php else : ?>
        <title> <?= translate('YourOrder'); ?></title>
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<div class="container">

    <?php if (isset($confirmOrder) && !$confirmOrder) : ?>
        <h1> <?= translate('Cart'); ?></h1>
    <?php else : ?>
        <h1> <?= translate('Order'); ?></h1>
    <?php endif; ?>

    <?php if (isset($confirmOrder) && $confirmOrder) : ?>
        <h2> Hello, <?= isset($_POST['name']) ? $_POST['name'] : ''; ?> !</h1>
            <p>We are processing your order. </p>
        <?php endif; ?>
        <?php if ($hasCartItems) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="content">
                    <form method="POST">
                        <div class="img">
                            <img src="http://localhost/training/Vanilla/assets/photos/<?= $row['imageSource'] ?>" alt="img">
                        </div>
                        <div class="details">
                            <p><?= translate('Title'); ?>: <?= $row['title']; ?></p>
                            <p><?= translate('Description'); ?>: <?= $row['description']; ?></p>
                            <p><?= translate('Price'); ?>: <?= $row['price']; ?></p>
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        </div>
                        <?php if (isset($tomail) && !$tomail && !$confirmOrder) : ?>
                            <div>
                                <button type="submit" class="RemoveBtn"><?= translate('Remove'); ?></button>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            <?php endwhile; ?>
            <?php if (isset($tomail) && !$tomail && isset($confirmOrder) && !$confirmOrder) : ?>
                <form class="checkOut" method="POST">
                    <input type="text" name="name" placeholder="<?= translate('Name'); ?>" value="<?= isset($_POST['name']) ? $_POST['name'] : ''; ?>">
                    <input type="text" name="contactDetails" placeholder="<?= translate('ContactDetails'); ?>" value="<?= isset($_POST['contactDetails']) ? $_POST['contactDetails'] : ''; ?>">
                    <textarea name="comments" placeholder="<?= translate('Comments'); ?>" cols="20" rows="4"><?= isset($_POST['comments']) ? $_POST['comments'] : ''; ?></textarea>
                    <?php if (isset($errors['message'])) : ?>
                        <p style="color:red;"><?= $errors['message']; ?></p>
                    <?php endif; ?>
                    <input type="submit" name="submit" value="<?= translate('Checkout'); ?>">
                </form>
            <?php elseif (isset($tomail) && $tomail) : ?>
                <h3><?= translate('CheckoutInformation'); ?>:</h3>
                <p><?= translate('Name'); ?>: <?= isset($_POST['name']) ? $_POST['name'] : ''; ?></p>
                <p><?= translate('Email'); ?>: <?= isset($_POST['contactDetails']) ? $_POST['contactDetails'] : ''; ?></p>
                <p><?= translate('Comments'); ?>: <?= isset($_POST['comments']) ? $_POST['comments'] : ''; ?></p>
            <?php endif; ?>
        <?php else : ?>
            <p><?= translate('EmptyCart'); ?></p>
        <?php endif; ?>
        <?php if (isset($tomail) && !$tomail && !$confirmOrder) : ?>
            <a href="index.php"><?= translate('GoToIndex'); ?></a>
        <?php endif; ?>
</div>

</html>