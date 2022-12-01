<?php
// Disable registration
header('Location: ?page=index');
exit;
?>

<?= Account::NoLogin(); ?>
<h1><?= $title; ?></h1>
