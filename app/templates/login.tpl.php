<?php if (!defined('ACCESS')) exit; ?>

<?php
if (Account::IsLoggedIn()) {
  header('Location: ?page=index');
}
?>

<div class="card text-dark mb-3">
  <div class="card-header"><i class="<?= $iconClass; ?>"></i> <?= $title; ?></div>
  <div class="card-body">
    <!-- <h5 class="card-title">Title</h5> -->

    <h5 class="text-center">
      <i class="<?= $iconClass; ?>"></i> <?= $subtitle; ?>
    </h5>
    <hr class="mydivider-center text-muted" />

    <?php
    if (isset($_POST['sign-in'])) {
      echo Account::Login($_POST['email'], $_POST['pass']);
    }
    ?>

    <form action="" method="post" style="margin: auto; max-width: 500px;">      
      <div class="input-group mb-3">
        <!-- <label for="email" class="form-label">Email</label> -->
        <span class="input-group-text bi bi-person" id="email-addon1"></span>
        <input
          type="text"
          name="email"
          class="form-control"
          id="email"
          placeholder="Email"
          aria-label="Email"
          aria-describedby="email-addon1">
      </div>
      <div class="input-group mb-3">
        <!-- <label for="password" class="form-label">Κωδικός</label> -->
        <span class="input-group-text bi bi-lock" id="password-addon1"></span>
        <input
          type="password"
          name="pass"
          class="form-control"
          id="password"
          placeholder="Κωδικός"
          aria-label="Password"
          aria-describedby="password-addon1">
      </div>
      <button type="submit" name="sign-in" class="btn btn-primary">
      <i class="bi bi-box-arrow-in-right"></i>
        Σύνδεση
      </button>
    </form>
  </div>
</div>