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
            $('#edit-user-result').html('<div class="alert alert-danger">Something went wrong.</div>');
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
            $('#edit-announcement-result').html('<div class="alert alert-danger">Something went wrong.</div>');
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
      $('#change-email-result').html('<div class="alert alert-danger">All fields are required.</div>');
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
      $('#change-password-result').html('<div class="alert alert-danger">All fields are required.</div>');
      resetBtn();
      return;
    }

    if (newpassword !== confirmpassword) {
      $('#change-password-result').html('<div class="alert alert-danger">Password confirmation failed.</div>');
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
      $('#register-result').html(`<div class="alert alert-danger">All fields are required.</div>`);
      resetBtn();
      return;
    }

    if (password !== confirmpassword) {
      $('#register-result').html(`<div class="alert alert-danger">Password confirmation failed.</div>`);
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
      $('#add-user-result').html(`<div class="alert alert-danger">All fields are required.</div>`);
      resetBtn();
      return;
    }

    if (password !== confirmpassword) {
      $('#add-user-result').html('<div class="alert alert-danger">Password confirmation failed.</div>')
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
      $('#edit-user-result').html(`<div class="alert alert-danger">All fields are required.</div>`);
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
                    ${announcement.is_project == 1 ? `<a href="?page=projects" class="card-link">Δείτε τις εργασίες</a>` : ''}
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
      $('#add-announcement-result').html(`<div class="alert alert-danger">All fields are required.</div>`);
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
      $('#edit-announcement-result').html(`<div class="alert alert-danger">All fields are required.</div>`);
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
            $('#documents-result').html('<div class="alert alert-danger">Δεν υπάρχουν αποτελέσματα.</div>');
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
                        <a href="#${document.id}" class="btn btn-danger btn-sm" onclick="javascript:app.DeleteDocument(${document.id});">
                          <i class="bi bi-trash3"></i>
                        </a>
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
                        Ανέβηκε στις ${document.creation_date}
                      </small>
                    </p>
                    ${document.project_id !== null ? `<a href="?page=projects" class="card-link">Δείτε τις εργασίες</a>` : ''}
                  </div>
                </div>
                <br />
              `;
            });

            $('#documents-result').html(result);

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
                      <a href="#${project.id}" class="btn btn-danger btn-sm" onclick="javascript:app.DeleteProject(${project.id});">
                        <i class="bi bi-trash3"></i>
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
                        Ανέβηκε στις ${project.creation_date}
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

  DeleteProject: function (id) {
    if (confirm('Είσαι σίγουρος;') == true) {
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

// Generate random string
function makeid(length) {
  var result = '';
  var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  var charactersLength = characters.length;
  for (var i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
}

// Remove element from array
function removeFromArray(arr, el) {
  arr.splice(arr.indexOf(el), 1);
  return arr;
}

// Dates
const weekDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
