<?php if (!defined('ACCESS')) exit; ?>
<div class="card text-dark mb-3">
  <div class="card-body">
    <h5 class="text-center">
      <i class="<?= $iconClass; ?>"></i>
      <?= $title; ?>
    </h5>
    <hr class="mydivider-center text-muted" />

    <form action="" method="post" style="margin: auto; max-width: 500px;">
      <?php
      if (isset($_POST['send'])) {
        echo Misc::Contact($_POST['name'], $_POST['subject'], $_POST['email'], $_POST['message']);
      }
      ?>

      <div class="form-floating mb-3">
        <input type="text" name="name" class="form-control" id="name" placeholder="John Doe" value="<?= (Account::IsLoggedIn()) ? Account::getAccount()->firstname . ' ' . Account::getAccount()->lastname : ''; ?>">
        <label for="name" class="form-label">Ονοματεπώνυμο</label>
      </div>
      <div class="form-floating mb-3">
        <input type="text" name="subject" class="form-control" id="subject" placeholder="Θέμα" maxlength="100">
        <label for="subject" class="form-label">Θέμα</label>
      </div>
      <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" value="<?= (Account::IsLoggedIn()) ? Account::getAccount()->email : ''; ?>">
        <label for="email" class="form-label">Email</label>
      </div>
      <div class="form-floating mb-3">
        <textarea class="form-control position-relative" name="message" id="message" placeholder="Θα χαρούμε να σε ακούσουμε! &#128522;" maxlength="1000" style="min-height:200px;"></textarea>
        <label for="message">Θα χαρούμε να σε ακούσουμε! &#128522;</label>
        <span id="message-chars" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">1000</span>
      </div>
      <div class="mx-auto" style="width:max-content;">
        <button type="submit" name="send" class="btn btn-outline-primary">
          <i class="bi bi-envelope"></i>
          Αποστολή
        </button>
      </div>
    </form>

    <div class="text-center">
      <hr class="mydivider-center text-muted" />
      <small class="text-muted py-3">
        Ή μπορείτε να μας στείλετε email στο <a href="mailto:<?= CONTACT_EMAIL; ?>" target="_blank"><?= CONTACT_EMAIL; ?></a>
      </small>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('#message').keyup(function() {
      $('#message-chars').html(1000 - $('#message').val().length)
    });

    $('#message').keydown(function() {
      $('#message-chars').html(1000 - $('#message').val().length)
    });
  });
</script>