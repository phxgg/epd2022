<?php if (!defined('ACCESS')) exit; ?>

<?= Account::LoginRequired(); ?>

<h1>
  <i class="<?= $iconClass; ?>"></i>
  <?= $title; ?>
</h1>

<div class="card text-dark mb-3">
  <div class="card-body">
    <?php if (Account::IsTutor()) : ?>
      <a href="<?= BASE_URL; ?>/?page=announcements" class="btn btn-sm btn-purple" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
        <i class="bi bi-plus"></i>
        Δημιουργία
      </a>
      <hr class="mydivider text-muted" />
    <?php endif; ?>

    <div id="loading" class="text-center">
      <div class="spinner-border text-primary m-5" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div id="announcements-result"></div>
  </div>
</div>


<!-- Add Announcement modal -->
<div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addAnnouncementModalLabel">
          <i class="bi bi-plus"></i>
          Δημιουργία ανακοίνωσης
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="add-announcement-result"></div>

          <div class="form-floating mb-3">
            <input type="text" name="add-title" class="form-control" id="add-title" placeholder="Τίτλος" aria-label="Τίτλος" aria-describedby="title-add-addon">
            <label for="add-title" class="form-label">
              Τίτλος
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <textarea style="height: 300px;" name="add-body" class="form-control" id="add-body" placeholder="Κείμενο" aria-label="Κείμενο" aria-describedby="body-add-addon"></textarea>
            <label for="add-body" class="form-label">
              Κείμενο
              <span class="text-danger">*</span>
            </label>
          </div>

          <!-- <div class="form-check form-switch mb-3">
            <input class="form-check-input" name="add-is-project" id="add-is-project" type="checkbox" value="">
            <label class="form-check-label" for="add-is-project">
              Είναι ανακοίνωση εργασίας
            </label>
          </div> -->

        </form>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" id="add-announcement-btn" class="btn btn-purple btn-sm">
          <i class="bi bi-plus"></i>
          Δημιουργία
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Announcement modal -->
<div class="modal fade" id="editAnnouncementModal" tabindex="-1" aria-labelledby="editAnnouncementModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAnnouncementModalLabel">
          <i class="bi bi-pencil-square"></i>
          Επεξεργασία ανακοίνωσης
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="edit-announcement-result"></div>

          <input type="hidden" id="edit-id" value="">
          
          <div class="form-floating mb-3">
            <input type="text" name="edit-title" class="form-control" id="edit-title" placeholder="Τίτλος" aria-label="Τίτλος" aria-describedby="title-edit-addon">
            <label for="edit-title" class="form-label">
              Τίτλος
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <textarea style="height: 300px;" name="edit-body" class="form-control" id="edit-body" placeholder="Κείμενο" aria-label="Κείμενο" aria-describedby="body-edit-addon"></textarea>
            <label for="edit-body" class="form-label">
              Κείμενο
              <span class="text-danger">*</span>
            </label>
          </div>

          <!-- <div class="form-check form-switch mb-3">
            <input class="form-check-input" name="edit-is-project" id="edit-is-project" type="checkbox" value="">
            <label class="form-check-label" for="edit-is-project">
              Είναι ανακοίνωση εργασίας
            </label>
          </div> -->

        </form>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" id="delete-announcement-btn" class="btn btn-danger btn-sm">
          <i class="bi bi-trash3"></i>
          Διαγραφή
        </button>
        <button type="button" id="edit-announcement-btn" class="btn btn-purple btn-sm">
          <i class="bi bi-pencil-square"></i>
          Αποθήκευση
        </button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  app.LoadAnnouncements();

  $('#add-announcement-btn').click(function() {
    app.AddAnnouncement();
  });

  $('#edit-announcement-btn').click(function() {
    app.EditAnnouncement();
  });

  $('#delete-announcement-btn').click(function () {
    app.DeleteAnnouncement();
  });
});
</script>
