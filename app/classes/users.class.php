<?php
class Users extends CMS
{
  public static function GetAllUsers()
  {
    global $mysqli;

    $users = $mysqli->query(
      'SELECT
        `id`,
        `firstname`,
        `lastname`,
        `email`,
        `creation_date`,
        `role`
      FROM `users` ORDER BY `role` DESC'
    );
    if ($users->num_rows == 0)
      return NULL;
    
    $usersArr = [];
    while ($user = $users->fetch_object()) {
      $usersArr[] = $user;
    }

    return $usersArr;
  }

  public static function GetTutors()
  {
    global $mysqli;

    $tutors = $mysqli->query(
      'SELECT
        `id`,
        `firstname`,
        `lastname`,
        `email`,
        `creation_date`,
        `role`
      FROM `users` WHERE `role` = 1'
    );
    if ($tutors->num_rows == 0)
      return NULL;
    
    $tutorsArr = [];
    while ($tutor = $tutors->fetch_object()) {
      $tutorsArr[] = $tutor;
    }

    return $tutorsArr;
  }

  public static function Fetch($uid)
  {
    global $mysqli;

    $uid = intval($uid);

    $data = $mysqli->query(sprintf(
      'SELECT
        `id`,
        `firstname`,
        `lastname`,
        `email`,
        `creation_date`,
        `role`
      FROM `users`
      WHERE `id` = %d',
      $uid
    ));

    if (!$data || $data->num_rows == 0)
      return NULL;

    return $data->fetch_object();
  }

  // return array(data, message)
  public static function AddUser($firstname, $lastname, $email, $pass, $confirmpass, $role)
  {
    global $mysqli;

    $role = intval($role);
    // if rank intval is not 'student' or 'tutor', then automatically convert to 'student'
    if ($role != 0 && $role != 1) $role = 0; 

    if (Misc::MultipleEmpty($firstname, $lastname, $email, $pass, $confirmpass))
      return [NULL, 'All fields are required.'];

    if ($pass !== $confirmpass)
      return [NULL, 'Password confirmation failed.'];

    if (!Misc::IsValidEmail($email))
      return [NULL, 'Invalid email format.'];

    $email = $mysqli->real_escape_string($email);

    $account = $mysqli->query(sprintf('SELECT * FROM `users` WHERE `email` = "%s"',
      $email
    ));

    if ($account->num_rows != 0)
      return [NULL, 'This email already exists. Choose something else.'];

    $account = $account->fetch_object();

    $firstname = ucfirst(strtolower($mysqli->real_escape_string($firstname)));
    $lastname = ucfirst(strtolower($mysqli->real_escape_string($lastname)));

    $salt = Misc::GenerateRandomString(32);
    $query = $mysqli->query(sprintf(
      'INSERT INTO `users` (`firstname`, `lastname`, `email`, `password`, `salt`, `role`) VALUES("%s", "%s", "%s", "%s", "%s", %d)',
      $firstname,
      $lastname,
      $email,
      md5(md5($mysqli->real_escape_string($pass)) . $salt),
      $salt,
      $role
    ));
    
    if ($query)
      return ['ok', 'User added successfully'];

    return [NULL, 'Something went wrong.'];
  }

  public static function EmailExists($email)
  {
    global $mysqli;

    $email = $mysqli->real_escape_string($email);

    $exists = $mysqli->query(sprintf('SELECT `id` FROM `users` WHERE `email` = "%s"', $email));

    if ($exists->num_rows != 0)
      return true;
    return false;
  }

  public static function EditUser($uid, $firstname, $lastname, $email, $role)
  {
    global $mysqli;

    $uid = intval($uid);
    $role = intval($role);
    
    if ($role != 0 && $role != 1 ) $role = 0; 

    if (Misc::MultipleEmpty($firstname, $lastname, $email))
      return [NULL, 'All fields are required.'];

    if (!Misc::IsValidEmail($email))
      return [NULL, 'Invalid email format.'];

    // Get current user info
    $fetch = Users::Fetch($uid);
    if (empty($fetch))
      return [NULL, 'User not found.'];

    // If the email has been changed then perform a check so we don't update the user with a value that is already used
    if ($email != $fetch->email)
      if (self::EmailExists($email))
        return [NULL, 'This email already exists.'];

    $email = $mysqli->real_escape_string($email);

    $firstname = ucfirst(strtolower($mysqli->real_escape_string($firstname)));
    $lastname = ucfirst(strtolower($mysqli->real_escape_string($lastname)));

    $query = $mysqli->query(sprintf(
      'UPDATE `users`
      SET
        `firstname` = "%s",
        `lastname` = "%s",
        `email` = "%s",
        `role` = %d
      WHERE `id` = %d',
      $firstname,
      $lastname,
      $email,
      $role,
      $uid
    ));
    
    if ($query)
      return ['ok', 'User edited successfully'];

    return [NULL, 'Something went wrong.'];
  }

  public static function DeleteUser($uid)
  {
    global $mysqli;

    $fetch = Users::Fetch($uid);
    if (empty($fetch))
      return [NULL, 'This user does not exist.'];
    
    $uid = intval($uid);

    $query = $mysqli->query(sprintf('DELETE FROM `users` WHERE `id` = %d', $uid));

    // // Also delete all bookings and stores associated with this user
    // $ownedStores = Stores::GetOwnedStoresByUserId($uid);

    // foreach($ownedStores as $store) {
    //   // delete store. This function also deletes all bookings associated with this store
    //   Stores::DeleteStore($store->id);
    // }

    // $deleteUserBookings = $mysqli->query(sprintf('DELETE FROM `bookings` WHERE `uid` = %d', $uid));

    if ($query) // && $deleteUserBookings
      return ['ok', 'Deleted user!'];

    return [NULL, 'Something went wrong.'];
  }

  // public static function OwnsStore($storeid, $uid = -1)
  // {
  //   global $mysqli;

  //   $uid = ($uid == -1) ? intval($_SESSION['uid']) : intval($uid);

  //   $storeid = intval($storeid);

  //   $owns = $mysqli->query(sprintf(
  //     'SELECT `id` FROM `stores` WHERE `id` = %d AND `added_by` = %d',
  //     $storeid,
  //     $uid
  //   ));

  //   if ($owns->num_rows != 0 || Account::IsAdmin())
  //     return true;
  //   return false;
  // }
}
