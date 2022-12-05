<?php if (!defined('ACCESS')) exit; ?>

<?php
// Disable registration
header('Location: '.BASE_URL.'/?page=index');
exit;
?>

<?= Account::NoLogin(); ?>
<h1><?= $title; ?></h1>
