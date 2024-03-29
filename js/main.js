var app = {
  _editUserModal: function (uid) {
    $('#edit-user-result').html('');

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-user',
        'uid': uid
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#edit-user-result').html('<div class="alert alert-danger">Κάτι πήγε στραβά.</div>');
            break;
          case 1:
            break;
          case 2:
            var user = res.data;

            $('#edit-id').val(user.id);
            $('#edit-firstname').val(user.firstname);
            $('#edit-lastname').val(user.lastname);
            $('#edit-email').val(user.email);
            $('#edit-role').val(user.role);

            break;
        }
      }
    });
  },

  _editAnnouncementModal: function (id) {
    $('#edit-announcement-result').html('');

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-announcement',
        'id': id
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#edit-announcement-result').html('<div class="alert alert-danger">Κάτι πήγε στραβά.</div>');
            break;
          case 1:
            break;
          case 2:
            var announcement = res.data;

            $('#edit-id').val(announcement.id);
            $('#edit-title').val(announcement.title);
            $('#edit-body').val(announcement.body);
            $('#edit-is-project').prop('checked', announcement.is_project == 1);

            break;
        }
      }
    });
  },

  _editProjectModal: function (id) {
    $('#edit-project-result').html('');

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-project',
        'id': id
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#edit-project-result').html('<div class="alert alert-danger">Κάτι πήγε στραβά.</div>');
            break;
          case 1:
            break;
          case 2:
            var project = res.data;

            $('#edit-id').val(project.id);
            $('#edit-title').val(project.title);
            $('#edit-body').val(project.body);
            $('#edit-deadline-date').val(project.deadline_date);

            break;
        }
      }
    });
  },

  _documentModal: function (id) {
    $('#delete-document-result').html('');

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-document',
        'id': id
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#modal-document-body').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            var document = res.data;

            var result = '';

            result += `
            <input type="hidden" id="modal-edit-document-id" value="${document.id}">

            <div id="modal-edit-document-result"></div>

            <form id="modal-form-upload-document" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="modal-upload-document" class="form-label">
                  <i class="bi bi-file-earmark-richtext"></i>
                  Αλλαγή αρχείου
                </label>
                <input class="form-control" type="file" id="modal-upload-document">
                <small class="form-text text-muted">Μέγιστο μέγεθος αρχείου: 1MB</small>
              </div>

              <div class="form-floating mb-3">
                <textarea style="height: 200px;" name="modal-edit-description" class="form-control" id="modal-edit-description" placeholder="Κείμενο" aria-label="Κείμενο" aria-describedby="modal-description-edit-addon">${document.description}</textarea>
                <label for="modal-edit-description" class="form-label">
                  Περιγραφή
                  <span class="text-danger">*</span>
                </label>
              </div>
            </form>
            
            <a href="download-document.php?id=${document.id}" class="text-decoration-none">
              <div class="card border-info" style="width: 18rem;">
                <div class="card-body">
                  <h5 class="card-title text-dark">
                    <i class="bi bi-file-earmark-richtext"></i>
                    ${document.filename}.${document.extension}
                  </h5>
                  <h6 class="card-subtitle mb-2 text-muted">Λήψη</h6>
                </div>
              </div>
            </a>
            `;

            $('#modal-document-body').html(result);

            $('#modal-document-footer').html(`
            <span id="modal-delete-document-result" class="text-muted"></span>

            <button type="button" class="btn btn-danger btn-sm" id="modal-delete-document-btn">
              <i class="bi bi-trash3"></i>
              Διαγραφή
            </button>

            <button type="button" class="btn btn-purple btn-sm" id="modal-edit-document-btn">
              <i class="bi bi-send"></i>
              Υποβολή
            </button>
            `);

            $('#init').html(`
            <script type="text/javascript">
              $('#modal-edit-document-btn').click(function() {
                app.EditDocument();
              });

              $('#modal-delete-document-btn').click(function() {
                app.DeleteDocument(${document.id});
              });
            </script>
            `);
            break;
        }
      }
    });
  },

  ChangeEmail: function () {
    const resetBtn = () => {
      $('#change-email-btn').removeClass('disabled');
      $('#change-email-btn').html(`
        <i class="bi bi-pencil-square"></i>
        Αλλαγή
      `);
    }

    $('#change-email-btn').addClass('disabled');
    $('#change-email-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var currentpassword = $('#email-currpass').val();
    var newemail = $('#email-newemail').val();

    if (!currentpassword || !newemail) {
      $('#change-email-result').html('<div class="alert alert-danger">Όλα τα πεδία είναι υποχρεωτικά.</div>');
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'change-email',
        'currentpassword': currentpassword,
        'newemail': newemail
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#change-email-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#change-email-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }
      }
    });

    resetBtn();
  },

  ChangePassword: function () {
    const resetBtn = () => {
      $('#change-password-btn').removeClass('disabled');
      $('#change-password-btn').html(`
        <i class="bi bi-pencil-square"></i>
        Αλλαγή
      `);
    }

    $('#change-password-btn').addClass('disabled');
    $('#change-password-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var currentpassword = $('#pass-currpass').val();
    var newpassword = $('#pass-newpass').val();
    var confirmpassword = $('#pass-confirmpass').val();

    if (!currentpassword || !newpassword || !confirmpassword) {
      $('#change-password-result').html('<div class="alert alert-danger">Όλα τα πεδία είναι υποχρεωτικά.</div>');
      resetBtn();
      return;
    }

    if (newpassword !== confirmpassword) {
      $('#change-password-result').html('<div class="alert alert-danger">Οι κωδικοί δεν ταιριάζουν.</div>');
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'change-password',
        'currentpassword': currentpassword,
        'newpassword': newpassword,
        'confirmpassword': confirmpassword
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#change-password-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#change-password-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }
      }
    });

    resetBtn();
  },

  Register: function () {
    const resetBtn = () => {
      $('#register-btn').removeClass('disabled');
      $('#register-btn').html(`
        <i class="bi bi-person-plus"></i>
        Εγγραφή
      `);
    }

    $('#register-btn').addClass('disabled');
    $('#register-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var firstname = $('#firstname').val();
    var lastname = $('#lastname').val();
    var email = $('#email').val();
    var password = $('#password').val();
    var confirmpassword = $('#confirmpassword').val();

    if (!firstname
      || !lastname
      || !email
      || !password
      || !confirmpassword) {
      $('#register-result').html(`<div class="alert alert-danger">Όλα τα πεδία είναι υποχρεωτικά.</div>`);
      resetBtn();
      return;
    }

    if (password !== confirmpassword) {
      $('#register-result').html(`<div class="alert alert-danger">Οι κωδικοί δεν ταιριάζουν.</div>`);
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'register',
        'firstname': firstname,
        'lastname': lastname,
        'email': email,
        'password': password,
        'confirmpassword': confirmpassword
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#register-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#register-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }
      }
    });

    resetBtn();
  },

  LoadUsers: function () {
    $('#loading').show();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-users'
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            // $('#users-result').html('<div class="alert alert-danger">Δεν υπάρχουν αποτελέσματα.</div>');
            break;
          case 1:
            break;
          case 2:
            result = '';

            res.data.forEach(user => {
              var rankToStr = '';
              switch (user.role) {
                case '0':
                  rankToStr = '<span class="badge bg-dark">Φοιτητής</span>';
                  break;
                case '1':
                  rankToStr = '<span class="badge bg-primary">Καθηγητής</span>'
                  break;
              }

              result += `
              <tr>
                <td>${user.email}</td>
                <td>${user.firstname} ${user.lastname}</td>
                <td>${rankToStr}</td>
                <td>
                  <a href="#${user.id}" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal" onclick="javascript:app._editUserModal(${user.id});">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                </td>
              </tr>
              `;
            });

            $('#users-result').html(result);

            $('#myTable').DataTable({
              'order': [[2, 'asc']]
            });

            break;
        }
      }
    });

    $('#loading').hide();
  },

  AddUser: function () {
    const resetBtn = () => {
      $('#add-user-btn').removeClass('disabled');
      $('#add-user-btn').html(`
        <i class="bi bi-person-plus"></i>
        Προσθήκη
      `);
    }

    $('#add-user-btn').addClass('disabled');
    $('#add-user-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);
    var firstname = $('#firstname').val();
    var lastname = $('#lastname').val();
    var email = $('#email').val();
    var password = $('#password').val();
    var confirmpassword = $('#confirmpassword').val();
    var role = $('#role').find(':selected').val();

    if (!firstname
      || !lastname
      || !email
      || !password
      || !confirmpassword) {
      $('#add-user-result').html(`<div class="alert alert-danger">Όλα τα πεδία είναι υποχρεωτικά.</div>`);
      resetBtn();
      return;
    }

    if (password !== confirmpassword) {
      $('#add-user-result').html('<div class="alert alert-danger">Οι κωδικοί δεν ταιριάζουν.</div>')
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'add-user',
        'firstname': firstname,
        'lastname': lastname,
        'email': email,
        'password': password,
        'confirmpassword': confirmpassword,
        'role': role
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#add-user-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#add-user-result').html(`<div class="alert alert-success">${res.data}</div>`);

            $('#firstname').val('');
            $('#lastname').val('');
            $('#email').val('');
            $('#password').val('');
            $('#confirmpassword').val('');
            break;
        }
      }
    });

    resetBtn();
  },

  EditUser: function () {
    const resetBtn = () => {
      $('#edit-user-btn').removeClass('disabled');
      $('#edit-user-btn').html(`
        <i class="bi bi-pencil-square"></i>
        Αποθήκευση
      `);
    }

    $('#edit-user-btn').addClass('disabled');
    $('#edit-user-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var uid = $('#edit-id').val();
    var firstname = $('#edit-firstname').val();
    var lastname = $('#edit-lastname').val();
    var email = $('#edit-email').val();
    var role = $('#edit-role').find(':selected').val();

    if (!firstname
      || !lastname
      || !email) {
      $('#edit-user-result').html(`<div class="alert alert-danger">Όλα τα πεδία είναι υποχρεωτικά.</div>`);
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'edit-user',
        'uid': uid,
        'firstname': firstname,
        'lastname': lastname,
        'email': email,
        'role': role
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#edit-user-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#edit-user-result').html(`<div class="alert alert-success">${res.data}</div>`);
            app.LoadUsers();
            break;
        }
      }
    });

    resetBtn();
  },

  DeleteUser: function () {
    if (confirm('Είσαι σίγουρος;') == true) {
      var uid = $('#edit-id').val();

      $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: {
          'action': 'delete-user',
          'uid': uid
        },
        success: function (res) {
          switch (res.status) {
            case 0:
              $('#edit-user-result').html(`<div class="alert alert-danger">${res.data}</div>`);
              break;
            case 1:
              break;
            case 2:
              location.reload();
              break;
          }
        }
      });
    } else {
      return;
    }
  },

  LoadAnnouncements: function () {
    $('#loading').show();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-announcements'
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#announcements-result').html('<div class="alert alert-danger">Δεν υπάρχουν αποτελέσματα.</div>');
            break;
          case 1:
            break;
          case 2:
            result = '';

            res.data.forEach(announcement => {
              result += `
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">
                      ${announcement.display_edit_button ? `
                        <a href="#${announcement.id}" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editAnnouncementModal" onclick="javascript:app._editAnnouncementModal(${announcement.id});">
                          <i class="bi bi-pencil-square"></i>
                        </a>
                      ` : ''}
                      ${announcement.is_project == 1 ? `<span class="badge bg-primary">Εργασία ${announcement.projectId}</span>` : ''}
                      ${announcement.title}
                    </h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                      Ημερομηνία: ${new Date(announcement.creation_date).toLocaleDateString('el-GR', dateOptions)}
                    </h6>
                    <p class="card-text">
                      ${announcement.body}
                    </p>
                    ${announcement.is_project == 1 ? `<a href="index.php?page=projects" class="card-link">Δείτε τις εργασίες</a>` : ''}
                  </div>
                </div>
                <br />
              `;
            });

            $('#announcements-result').html(result);

            break;
        }
      }
    });

    $('#loading').hide();
  },

  AddAnnouncement: function () {
    const resetBtn = () => {
      $('#add-announcement-btn').removeClass('disabled');
      $('#add-announcement-btn').html(`
        <i class="bi bi-person-plus"></i>
        Δημιουργία
      `);
    }

    $('#add-announcement-btn').addClass('disabled');
    $('#add-announcement-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var title = $('#add-title').val();
    var body = $('#add-body').val();
    // var isProject = ($('#add-is-project').is(':checked')) ? 1 : 0;

    if (!title
      || !body) {
      $('#add-announcement-result').html(`<div class="alert alert-danger">Όλα τα πεδία είναι υποχρεωτικά.</div>`);
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'add-announcement',
        'title': title,
        'body': body,
        // 'isproject': isProject
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#add-announcement-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#add-announcement-result').html(`<div class="alert alert-success">${res.data}</div>`);

            $('#add-title').val('');
            $('#add-body').val('');
            $('#add-is-project').prop('checked', false);

            app.LoadAnnouncements();
            break;
        }
      }
    });

    resetBtn();
  },

  EditAnnouncement: function () {
    const resetBtn = () => {
      $('#edit-announcement-btn').removeClass('disabled');
      $('#edit-announcement-btn').html(`
        <i class="bi bi-pencil-square"></i>
        Αποθήκευση
      `);
    }

    $('#edit-announcement-btn').addClass('disabled');
    $('#edit-announcement-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var id = $('#edit-id').val();
    var title = $('#edit-title').val();
    var body = $('#edit-body').val();
    // var isProject = $('#edit-is-project').is(':checked') ? 1 : 0;

    if (!title
      || !body) {
      $('#edit-announcement-result').html(`<div class="alert alert-danger">Όλα τα πεδία είναι υποχρεωτικά.</div>`);
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'edit-announcement',
        'id': id,
        'title': title,
        'body': body,
        // 'isproject': isProject,
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#edit-announcement-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#edit-announcement-result').html(`<div class="alert alert-success">${res.data}</div>`);
            app.LoadAnnouncements();
            break;
        }
      }
    });

    resetBtn();
  },

  DeleteAnnouncement: function () {
    if (confirm('Είσαι σίγουρος;') == true) {
      var id = $('#edit-id').val();

      $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: {
          'action': 'delete-announcement',
          'id': id
        },
        success: function (res) {
          switch (res.status) {
            case 0:
              $('#edit-announcement-result').html(`<div class="alert alert-danger">${res.data}</div>`);
              break;
            case 1:
              break;
            case 2:
              location.reload();
              break;
          }
        }
      });
    } else {
      return;
    }
  },

  LoadDocuments: function () {
    $('#loading').show();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-documents'
      },
      success: function (res) {
        console.log(res);

        switch (res.status) {
          case 0:
            $('#document-result').html('<div class="alert alert-danger">Δεν υπάρχουν αποτελέσματα.</div>');
            break;
          case 1:
            break;
          case 2:
            result = '';

            res.data.forEach(document => {
              result += `
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">
                      ${document.display_edit_button ? `
                      <span data-bs-toggle="modal" data-bs-target="#documentModal">
                        <a href="#${document.id}" class="btn btn-success btn-sm" onclick="javascript:app._documentModal(${document.id});">
                          <i class="bi bi-pencil-square"></i>
                        </a>
                      </span>
                      ` : ''}
                      ${document.project_id !== null ? `<span class="badge bg-primary">Εργασία ${document.project_id}</span>` : ''}
                      ${document.filename}.${document.extension}
                    </h5>
                    <p class="card-text">
                      ${document.description}
                      <hr />

                      <a class="btn btn-primary btn-sm" href="download-document.php?id=${document.id}">
                        <i class="bi bi-download"></i>
                        Λήψη
                      </a>
                      <br />

                      <small class="text-muted ${document.display_edit_button ? 'text-end' : ''}">
                        Ανέβηκε στις ${new Date(document.creation_date).toLocaleString('el-GR', dateOptions)}
                      </small>
                    </p>
                    ${document.project_id !== null ? `<a href="index.php?page=projects" class="card-link">Δείτε τις εργασίες</a>` : ''}
                  </div>
                </div>
                <br />
              `;
            });

            $('#document-result').html(result);

            break;
        }
      }
    });

    $('#loading').hide();
  },

  UploadDocument: function () {
    const resetBtn = () => {
      $('#upload-document-btn').removeClass('disabled');
      $('#upload-document-btn').html(`
        <i class="bi bi-send"></i>
        Προσθήκη
      `);
    }

    $('#upload-document-btn').addClass('disabled');
    $('#upload-document-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var document = $('#upload-document').prop('files')[0];
    var description = $('#upload-description').val();

    var formData = new FormData();
    formData.append('action', 'upload-document');
    formData.append('document', document);
    formData.append('description', description);

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: formData,
      processData: false,
      contentType: false,
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#upload-document-result').removeClass('alert-info');
            $('#upload-document-result').removeClass('alert-success');
            $('#upload-document-result').addClass('alert-danger');
            $('#upload-document-result').addClass('alert');
            $('#upload-document-result').html(res.data);
            break;
          case 1:
            break;
          case 2:
            $('#upload-document-result').removeClass('alert-info');
            $('#upload-document-result').removeClass('alert-danger');
            $('#upload-document-result').addClass('alert-success');
            $('#upload-document-result').addClass('alert');
            $('#upload-document-result').html(res.data);

            location.reload();
            break;
        }

        resetBtn();
      }
    });
  },

  EditDocument: function () {
    const resetBtn = () => {
      $('#modal-edit-document-btn').removeClass('disabled');
      $('#modal-edit-document-btn').html(`
        <i class="bi bi-send"></i>
        Υποβολή
      `);
    }

    $('#modal-edit-document-btn').addClass('disabled');
    $('#modal-edit-document-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var id = $('#modal-edit-document-id').val();
    var document = $('#modal-upload-document').prop('files')[0] || null;
    var description = $('#modal-edit-description').val();

    var formData = new FormData();
    formData.append('action', 'edit-document');
    formData.append('id', id);
    formData.append('document', document);
    formData.append('description', description);

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: formData,
      processData: false,
      contentType: false,
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#modal-edit-document-result').removeClass('alert-info');
            $('#modal-edit-document-result').removeClass('alert-success');
            $('#modal-edit-document-result').addClass('alert-danger');
            $('#modal-edit-document-result').addClass('alert');
            $('#modal-edit-document-result').html(res.data);
            break;
          case 1:
            break;
          case 2:
            $('#modal-edit-document-result').removeClass('alert-info');
            $('#modal-edit-document-result').removeClass('alert-danger');
            $('#modal-edit-document-result').addClass('alert-success');
            $('#modal-edit-document-result').addClass('alert');
            $('#modal-edit-document-result').html(res.data);

            location.reload();
            break;
        }

        resetBtn();
      }
    });
  },

  DeleteDocument: function (id) {
    if (confirm('Είσαι σίγουρος;') == true) {
      $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: {
          'action': 'delete-document',
          'id': id
        },
        success: function (res) {
          switch (res.status) {
            case 0:
              alert(res.data);
              break;
            case 1:
              break;
            case 2:
              location.reload();
              break;
          }
        }
      });
    } else {
      return;
    }
  },

  LoadProjects: function () {
    $('#loading').show();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-projects'
      },
      success: function (res) {
        console.log(res);

        switch (res.status) {
          case 0:
            $('#projects-result').html('<div class="alert alert-danger">Δεν υπάρχουν αποτελέσματα.</div>');
            break;
          case 1:
            break;
          case 2:
            result = '';

            res.data.forEach(project => {
              result += `
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">
                      ${project.display_edit_button ? `
                      <a href="#${project.id}" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editProjectModal" onclick="javascript:app._editProjectModal(${project.id});">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      ` : ''}
                      <span class="badge bg-dark">${project.id}</span>
                      ${project.title}
                    </h5>
                    <p class="card-text">
                      ${project.body}
                      <hr />
                      Προθεσμία:
                      ${new Date(project.deadline_date) < Date.now() ? `
                        <span class="badge bg-secondary">Έχει λείξει</span>
                      ` : `
                      <span class="badge bg-primary">${new Date(project.deadline_date).toLocaleDateString('el-GR', dateOptions)}</span>
                      `}
                      <hr />
                      ${project.document[0] === 'ok' ? `
                      <a class="btn btn-primary btn-sm" href="download-document.php?id=${project.document[1].id}">
                        <i class="bi bi-download"></i>
                        Λήψη εκφώνησης
                      </a>
                      ` : `
                      <span class="badge bg-danger">Δεν υπάρχει αρχείο</span>
                      `}
                      <br />

                      <small class="text-muted ${project.display_edit_button ? 'text-end' : ''}">
                        Ανέβηκε στις ${new Date(project.creation_date).toLocaleString('el-GR', dateOptions)}
                      </small>
                    </p>
                  </div>
                </div>
                <br />
              `;
            });

            $('#projects-result').html(result);

            break;
        }
      }
    });

    $('#loading').hide();
  },

  AddProject: function () {
    const resetBtn = () => {
      $('#add-project-btn').removeClass('disabled');
      $('#add-project-btn').html(`
        <i class="bi bi-send"></i>
        Προσθήκη
      `);
    }

    $('#add-project-btn').addClass('disabled');
    $('#add-project-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var document = $('#upload-document').prop('files')[0];
    var title = $('#add-title').val();
    var body = $('#add-body').val();
    var deadline = $('#add-deadline-date').val();

    var formData = new FormData();
    formData.append('action', 'add-project');
    formData.append('document', document);
    formData.append('title', title);
    formData.append('body', body);
    formData.append('deadline', deadline);

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: formData,
      processData: false,
      contentType: false,
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#add-project-result').removeClass('alert-info');
            $('#add-project-result').removeClass('alert-success');
            $('#add-project-result').addClass('alert-danger');
            $('#add-project-result').addClass('alert');
            $('#add-project-result').html(res.data);
            break;
          case 1:
            break;
          case 2:
            $('#add-project-result').removeClass('alert-info');
            $('#add-project-result').removeClass('alert-danger');
            $('#add-project-result').addClass('alert-success');
            $('#add-project-result').addClass('alert');
            $('#add-project-result').html(res.data);

            location.reload();
            break;
        }

        resetBtn();
      }
    });
  },

  EditProject: function () {
    const resetBtn = () => {
      $('#edit-project-btn').removeClass('disabled');
      $('#edit-project-btn').html(`
        <i class="bi bi-pencil-square"></i>
        Αποθήκευση
      `);
    }

    $('#edit-project-btn').addClass('disabled');
    $('#edit-project-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var id = $('#edit-id').val();
    var title = $('#edit-title').val();
    var body = $('#edit-body').val();
    // var isProject = $('#edit-is-project').is(':checked') ? 1 : 0;

    if (!title
      || !body) {
      $('#edit-project-result').html(`<div class="alert alert-danger">Όλα τα πεδία είναι υποχρεωτικά.</div>`);
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'edit-project',
        'id': id,
        'title': title,
        'body': body,
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#edit-project-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#edit-project-result').html(`<div class="alert alert-success">${res.data}</div>`);
            app.LoadProjects();
            break;
        }
      }
    });

    resetBtn();
  },

  DeleteProject: function () {
    if (confirm('Είσαι σίγουρος;') == true) {
      var id = $('#edit-id').val();

      $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: {
          'action': 'delete-project',
          'id': id
        },
        success: function (res) {
          switch (res.status) {
            case 0:
              alert(res.data);
              break;
            case 1:
              break;
            case 2:
              location.reload();
              break;
          }
        }
      });
    } else {
      return;
    }
  },
  
};

// Dates
const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric' };
