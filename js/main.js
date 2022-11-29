var app = {
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

  Register: function() {
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

// Week days to string
const weekDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
