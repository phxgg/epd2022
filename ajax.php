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
//   reply(StatusCodes::Error, 'No access.');

// List of allowed actions
$allowed_actions = [
  'load-user',
  'load-users',
  'load-announcements',
  'load-announcement',
  'load-documents',
  'load-document',
  'load-projects',
  'load-project',

  'change-email',
  'change-password',
  // 'register',

  'add-user',
  'add-announcement',
  'add-project',

  'edit-user',
  'edit-announcement',
  'edit-document',
  'edit-project',

  'delete-document',
  'delete-project',
  'delete-user',
  'delete-announcement',

  'upload-document',
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
      reply(StatusCodes::Error, 'No access.');

    if (!isset($_POST['uid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Users::Fetch($_POST['uid']);

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'load-announcement':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'No access.');

    if (!isset($_POST['id']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Announcements::Fetch($_POST['id']);

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'load-users':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'No access.');

    $data = Users::GetAllUsers();

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'load-announcements':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'No access.');

    $data = Announcements::GetAllAnnouncements();

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'change-email':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'No access.');

    if (!isset($_POST['currentpassword']) || !isset($_POST['newemail']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Account::ChangeEmail($_POST['currentpassword'], $_POST['newemail']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'change-password':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'No access.');

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
      reply(StatusCodes::Error, 'No access.');

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
      reply(StatusCodes::Error, 'No access.');

    if (
      !isset($_POST['title'])
      || !isset($_POST['body'])
    )
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Announcements::AddAnnouncement(
      $_POST['title'],
      $_POST['body'],
      0,
      NULL
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'add-project':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'No access.');

    if (
      !isset($_POST['title'])
      || !isset($_POST['body'])
      || !isset($_POST['deadline'])
      || !isset($_FILES['document'])
    )
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Projects::AddProject(
      $_POST['title'],
      $_POST['body'],
      $_POST['deadline'],
      $_FILES['document']
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'edit-user':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'No access.');

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
  case 'load-documents':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'No access.');

    $data = Documents::GetAllDocuments();

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'load-document':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'You cannot access this.');

    if (!isset($_POST['id']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Documents::LoadDocument($_POST['id']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'load-projects':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'No access.');

    $data = Projects::GetAllProjects();

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'load-project':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'You cannot access this.');

    if (!isset($_POST['id']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Projects::LoadProject($_POST['id']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'edit-project':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'No access.');

    // $isproject = 0;
    // if (isset($_POST['isproject'])) $isproject = $_POST['isproject'];

    if (
      !isset($_POST['id'])
      || !isset($_POST['title'])
      || !isset($_POST['body'])
    )
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Projects::EditProject(
      $_POST['id'],
      $_POST['title'],
      $_POST['body']
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'delete-document':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'You cannot access this.');

    if (!isset($_POST['id']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Documents::DeleteDocument($_POST['id']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'delete-project':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'You cannot access this.');

    if (!isset($_POST['id']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Projects::DeleteProject($_POST['id']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'upload-document':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'You cannot access this.');

    if (!isset($_FILES['document']) || !isset($_POST['description']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Documents::UploadDocument($_FILES['document'], $_POST['description'], NULL);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'edit-document':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'You cannot access this.');

    if (!isset($_POST['id']) || !isset($_POST['description']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $file = NULL;
    if (isset($_FILES['document'])) $file = $_FILES['document'];

    $update_document = false;
    if ($file && !empty($_FILES['document']) && $_FILES['document']['size'] > 0)
      $update_document = true;

    $data = Documents::EditDocument($_POST['id'], $update_document, $file, $_POST['description']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'edit-announcement':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'No access.');

    // $isproject = 0;
    // if (isset($_POST['isproject'])) $isproject = $_POST['isproject'];

    if (
      !isset($_POST['id'])
      || !isset($_POST['title'])
      || !isset($_POST['body'])
    )
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Announcements::EditAnnouncement(
      $_POST['id'],
      $_POST['title'],
      $_POST['body']
      // $isproject
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'add-user':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'No access.');

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
      reply(StatusCodes::Error, 'No access.');

    if (!isset($_POST['uid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Users::DeleteUser($_POST['uid']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'delete-announcement':
    if (!Account::IsLoggedIn() || !Account::IsTutor())
      reply(StatusCodes::Error, 'No access.');

    if (!isset($_POST['id']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Announcements::DeleteAnnouncement($_POST['id']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
}
