<?php if (!defined('ACCESS')) exit; ?>

  <footer class="footer text-center">
    <hr class="mydivider-center text-muted" />
    <small class="text-muted py-3">
      <?= SITE_NAME; ?> &copy; 2022
    </small>
  </footer>
  
</main>

<script defer type="text/javascript">
$(document).ready(function() {
  $.fn.selectpicker.Constructor.BootstrapVersion = '5';

  // enable tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
</script>

</body>
</html>