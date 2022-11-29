<?php if (!defined('ACCESS')) exit; ?>

  <footer class="footer text-center">
    <hr class="mydivider-center text-muted" />
    <small class="text-muted py-3">
      <?= SITE_NAME; ?> &copy; 2022<br />
      <a href="/?page=contact" class="text-decoration-none">
        <i class="bi bi-chat-dots"></i>
        Επικοινωνία
      </a>
    </small>
  </footer>
  
</main>

<script defer type="text/javascript">
$.fn.selectpicker.Constructor.BootstrapVersion = '5';

$(document).ready(function() {
  // enable tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
</script>

</body>
</html>