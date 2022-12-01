<?php

header('Content-Type: application/json; charset=utf-8');

require_once 'app/init.php';

function reply($status, $data)
{
  die(json_encode(['status' => $status, 'data' => $data]));
}

// Die if the request method is not 'POST'
if (@$_SERVER['REQUEST_METHOD'] !== 'POST') die();

// if (!Account::IsLoggedIn())
//   reply(StatusCodes::Error, 'Not logged in.');

// List of allowed actions
$allowed_actions = [
  'load-user',
  'load-users',
  'load-announcements',
  'load-announcement',

  'change-email',
  'change-password',
  'register',

  'add-user',
  'add-announcement',

  'edit-user',
  'edit-announcement',

  'delete-documents',
  'delete-user',
  'delete-announcement',

  'view-documents',
  'upload-documents',
];

// Get current action
$action = $_POST['action'];

if (!isset($action))
  reply(StatusCodes::Error, 'No action.');

if (!in_array($action, $allowed_actions))
  reply(StatusCodes::Error, 'Invalid action.');

switch ($action) {
  case 'load-user':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'You are not a tutor.');

    if (!isset($_POST['uid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Users::Fetch($_POST['uid']);

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'load-announcement':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'You are not a tutor.');

    if (!isset($_POST['id']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Announcements::Fetch($_POST['id']);

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'load-users':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'You are not a tutor.');

    $data = Users::GetAllUsers();

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'load-announcements':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'Not logged in.');

    $data = Announcements::GetAllAnnouncements();

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'change-email':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'Not logged in.');

    if (!isset($_POST['currentpassword']) || !isset($_POST['newemail']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Account::ChangeEmail($_POST['currentpassword'], $_POST['newemail']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'change-password':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'Not logged in.');

    if (!isset($_POST['currentpassword']) || !isset($_POST['newpassword']) || !isset($_POST['confirmpassword']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Account::ChangePassword($_POST['currentpassword'], $_POST['newpassword'], $_POST['confirmpassword']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'register':
    if (Account::IsLoggedIn())
      reply(StatusCodes::Error, 'You are already logged in.');

    if (
      !isset($_POST['firstname'])
      || !isset($_POST['lastname'])
      || !isset($_POST['email'])
      || !isset($_POST['password'])
      || !isset($_POST['confirmpassword'])
    )
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Account::Register(
      $_POST['firstname'],
      $_POST['lastname'],
      $_POST['email'],
      $_POST['password'],
      $_POST['confirmpassword']
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'add-user':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'You are not a tutor.');

    $role = 0;
    if (isset($_POST['role'])) $role = $_POST['role'];

    if (
      !isset($_POST['firstname'])
      || !isset($_POST['lastname'])
      || !isset($_POST['email'])
      || !isset($_POST['password'])
      || !isset($_POST['confirmpassword'])
    )
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Users::AddUser(
      $_POST['firstname'],
      $_POST['lastname'],
      $_POST['email'],
      $_POST['password'],
      $_POST['confirmpassword'],
      $_POST['role']
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'add-announcement':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'You are not a tutor.');

    $isproject = 0;
    if (isset($_POST['isproject'])) $isproject = $_POST['isproject'];

    if (
      !isset($_POST['title'])
      || !isset($_POST['body'])
    )
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Announcements::AddAnnouncement(
      $_POST['title'],
      $_POST['body'],
      $isproject
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'edit-user':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'You are not a tutor.');

    $role = 0;
    if (isset($_POST['role'])) $role = $_POST['role'];

    if (
      !isset($_POST['uid'])
      || !isset($_POST['firstname'])
      || !isset($_POST['lastname'])
      || !isset($_POST['email'])
    )
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Users::EditUser(
      $_POST['uid'],
      $_POST['firstname'],
      $_POST['lastname'],
      $_POST['email'],
      $_POST['role']
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
    // case 'view-documents':
    //   if (!Account::IsLoggedIn())
    //     reply(StatusCodes::Error, 'You cannot access this.');

    //   if (!isset($_POST['sid']))
    //     reply(StatusCodes::Error, 'Invalid parameters.');

    //   $data = Stores::ViewDocuments($_POST['sid']);

    //   if ($data[0] === NULL)
    //     reply(StatusCodes::Error, $data[1]);
    //   elseif ($data[0] == 'info')
    //     reply(StatusCodes::Info, $data[1]);

    //   reply(StatusCodes::OK, $data[1]);
    //   break;

    // case 'delete-documents':
    //   if (!Account::IsLoggedIn() || !Account::IsTutor())
    //     reply(StatusCodes::Error, 'You cannot access this.');

    //   if (!isset($_POST['sid']))
    //     reply(StatusCodes::Error, 'Invalid parameters.');

    //   $data = Stores::DeleteDocuments($_POST['sid']);

    //   if ($data[0] === NULL)
    //     reply(StatusCodes::Error, $data[1]);

    //   reply(StatusCodes::OK, $data[1]);
    //   break;

    // case 'upload-documents':
    //   if (!Account::IsLoggedIn() || !Account::IsTutor())
    //     reply(StatusCodes::Error, 'You cannot access this.');

    //   if (!isset($_FILES['document']))
    //     reply(StatusCodes::Error, 'Invalid parameters.');

    //   $data = Stores::UploadDocuments($_FILES['document']);

    //   if ($data[0] === NULL)
    //     reply(StatusCodes::Error, $data[1]);

    //   reply(StatusCodes::OK, $data[1]);
    break;
  case 'edit-announcement':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'You are not a tutor.');

    $isproject = 0;
    if (isset($_POST['isproject'])) $isproject = $_POST['isproject'];

    if (
      !isset($_POST['id'])
      || !isset($_POST['title'])
      || !isset($_POST['body'])
    )
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Announcements::EditAnnouncement(
      $_POST['id'],
      $_POST['title'],
      $_POST['body'],
      $isproject
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
    break;
  case 'add-user':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'You are not a tutor.');

    $role = 0;
    if (isset($_POST['role'])) $rank = $_POST['role'];

    if (
      !isset($_POST['firstname'])
      || !isset($_POST['lastname'])
      || !isset($_POST['email'])
      || !isset($_POST['password'])
      || !isset($_POST['confirmpassword'])
    )
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Users::AddUser(
      $_POST['firstname'],
      $_POST['lastname'],
      $_POST['email'],
      $_POST['password'],
      $_POST['confirmpassword'],
      $_POST['role']
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'delete-user':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'You are not a tutor.');

    if (!isset($_POST['uid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Users::DeleteUser($_POST['uid']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'delete-announcement':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'You are not a tutor.');

    if (!isset($_POST['id']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Announcements::DeleteAnnouncement($_POST['id']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
}
