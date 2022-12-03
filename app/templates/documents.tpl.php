<?php if (!defined('ACCESS')) exit; ?>

<?= Account::LoginRequired(); ?>

<h1><?= $title; ?></h1>

<div class="card text-dark mb-3">
  <div class="card-body">
    <?php if (Account::IsTutor()) : ?>
      <a href="?page=documents" class="btn btn-sm btn-purple" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
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

    <div id="document-result"></div>
  </div>
</div>


<!-- Upload Document modal -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addDocumentModalLabel">
          <i class="bi bi-plus"></i>
          Προσθήκη
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-upload-document" enctype="multipart/form-data">
          <div id="upload-document-result"></div>

          <div class="mb-3">
            <label for="upload-document" class="form-label">
              <i class="bi bi-file-earmark-pdf"></i>
              Έγγραφο
              <span class="text-danger">*</span>
            </label>
            <input class="form-control" type="file" id="upload-document">
          </div>

          <div class="form-floating mb-3">
            <textarea style="height: 200px;" name="upload-description" class="form-control" id="upload-description" placeholder="Κείμενο" aria-label="Κείμενο" aria-describedby="description-add-addon"></textarea>
            <label for="upload-description" class="form-label">
              Περιγραφή
              <span class="text-danger">*</span>
            </label>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" id="upload-document-btn" class="btn btn-purple btn-sm">
          <i class="bi bi-plus"></i>
          Προσθήκη
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Document modal -->
<div class="modal fade" id="documentModal" aria-labelledby="modal-document-title" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-document-title">Έγγραφο</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="word-break: break-all;">
        <div id="modal-document-body"></div>
      </div>
      <div class="modal-footer">
        <div id="modal-document-footer"></div>
      </div>
    </div>
  </div>
</div>

<div id="init"></div>

<script type="text/javascript">
  $(document).ready(function() {
    app.LoadDocuments();

    $('#upload-document-btn').click(function() {
      app.UploadDocument();
    });
  });
</script>