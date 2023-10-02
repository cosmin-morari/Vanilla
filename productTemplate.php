<div class="container">
    <h3><?= translate('Product'); ?></h3>
    <?php if (isset($destination) && $destination === 'edit') : ?>
        <?php $row = $result->fetch_assoc(); ?>
        <input type="text" name="title" placeholder="<?= translate('Title'); ?>" value="<?= isset($_POST['title']) ? $_POST['title'] : $row['title'] ?>">
        <br>
        <br>
        <input type="text" name="description" placeholder="<?= translate('Description'); ?>" value="<?= isset($_POST['description']) ? $_POST['description'] : $row['description'] ?>">
        <br>
        <br>
        <input type="text" name="price" placeholder="<?= translate('Price'); ?>" value="<?= isset($_POST['price']) ? $_POST['price'] : $row['price'] ?>">
        <br>
        <br>
        <input type="text" name="img" placeholder="<?= translate('Image'); ?>">
        <br>
        <br>
    <?php elseif (isset($destination) && $destination === 'add') : ?>
        <input type="text" name="title" placeholder="<?= translate('Title'); ?>" value="<?= isset($_POST['title']) ? $_POST['title'] : '' ?>">
        <br>
        <br>
        <input type=" text" name="description" placeholder="<?= translate('Description'); ?>" value="<?= isset($_POST['description']) ? $_POST['description'] : '' ?>">
        <br>
        <br>
        <input type="text" name="price" placeholder="<?= translate('Price'); ?>" value="<?= isset($_POST['price']) ? $_POST['price'] : '' ?>">
        <br>
        <br>
        <input type="text" name="img" placeholder="<?= translate('Image'); ?>">
    <?php endif; ?>
    <input type="file" name="image" id="file" class="inputfile" />
    <?php if (isset($errors['message'])) : ?>
        <p style="color:red;"><?= $errors['message']; ?></p>
    <?php endif; ?>
    <br>
    <a href="products.php"><?= translate('Products'); ?></a>
    <input type="submit" name="save" value="<?= translate('Save'); ?>">
</div>