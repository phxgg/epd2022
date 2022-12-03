<?php if (!defined('ACCESS')) exit; ?>

<?= Account::LoginRequired(); ?>

<h1><?= $title; ?></h1>

<div class="card text-dark mb-3">
  <div class="card-body">
    <?php if (Account::IsTutor()) : ?>
      <a href="?page=projects" class="btn btn-sm btn-purple" data-bs-toggle="modal" data-bs-target="#addProjectModal">
        <i class="bi bi-plus"></i>
        Προσθήκη
      </a>
      <hr class="mydivider text-muted" />
    <?php endif; ?>

    <div id="loading" class="text-center">
      <div class="spinner-border text-primary m-5" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div id="projects-result"></div>
  </div>
</div>

<!-- Add Project modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProjectModalLabel">
          <i class="bi bi-plus"></i>
          Προσθήκη εργασίας
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="add-project-result"></div>

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

          <div class="mb-3">
            <label for="upload-document" class="form-label">
              <i class="bi bi-file-earmark-pdf"></i>
              Έγγραφο
              <span class="text-danger">*</span>
            </label>
            <input class="form-control" type="file" id="upload-document">
          </div>

          <div class="mb-3">
            <label for="add-deadline-date" class="form-label">
              <i class="bi bi-calendar"></i>
              Ημερομηνία παράδοσης
              <span class="text-danger">*</span>
            </label>
            <input type="date" name="add-deadline-date" class="form-control" id="add-deadline-date" placeholder="Ημερομηνία παράδοσης" aria-label="Ημερομηνία παράδοσης" aria-describedby="deadline-date-add-addon">
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" id="add-project-btn" class="btn btn-purple btn-sm">
          <i class="bi bi-plus"></i>
          Δημιουργία
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Project modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProjectModalLabel">
          <i class="bi bi-pencil-square"></i>
          Επεξεργασία ανακοίνωσης
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="edit-project-result"></div>

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

          <div class="form-check form-switch mb-3">
            <input class="form-check-input" name="edit-is-project" id="edit-is-project" type="checkbox" value="">
            <label class="form-check-label" for="edit-is-project">
              Είναι ανακοίνωση εργασίας
            </label>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" id="delete-project-btn" class="btn btn-danger btn-sm">
          <i class="bi bi-trash3"></i>
          Διαγραφή
        </button>
        <button type="button" id="edit-project-btn" class="btn btn-purple btn-sm">
          <i class="bi bi-pencil-square"></i>
          Αποθήκευση
        </button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    app.LoadProjects();

    $('#add-project-btn').click(function() {
      app.AddProject();
    });

    $('#edit-project-btn').click(function() {
      app.EditProject();
    });

    $('#delete-project-btn').click(function() {
      app.DeleteProject();
    });
  });
</script>