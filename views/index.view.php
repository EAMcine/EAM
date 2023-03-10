<!DOCTYPE <?php echo DOCTYPE ?>>
<html lang="<?php echo LANG ?>">
<?php include_once 'app/head.php'; ?>
<body>
<div id="loader">
    <div class="spinner"></div>
</div>
<div id="body" style="opacity: 0;">
    <?php include_once 'views/components/header.php'; ?>
    <h1><?php echo SITE_NAME ?></h1>

</div>
<?php include_once 'app/loader.html'; ?>
</body>
</html>