<?php
class Account extends CMS
{
  public static function IsLoggedIn()
  {
    return (isset($_SESSION['loggedIn']));
  }

  /**
   * Do not allow to access this page if user is logged in.
   */
  public static function NoLogin()
  {
    if (self::IsLoggedIn()) {
      header('Location: /?page=index');
      die();
    }
  }

  public static function LoginRequired()
  {
    if (!self::IsLoggedIn()) {
      header('Location: /?page=login');
      die();
    }
  }

  public static function IsTutor()
  {
    if (self::getAccount()->role == 1)
      return true;
    return false;
  }

  public static function TutorRequired()
  {
    if (!self::IsLoggedIn() || !self::IsTutor()) {
      header('Location: /?page=index');
      die();
    }
  }

  /**
   * Get the current logged in user.
   * If provided with an $id, then it will return the user with that id.
   */
  public static function getAccount($id = -1)
  {
    global $mysqli;

    $id = ($id == -1) ? intval($_SESSION['uid']) : intval($id);

    if (Account::IsLoggedIn()) {
      return $mysqli->query(sprintf('SELECT * FROM `users` WHERE `id` = %d', $id))->fetch_object();
    }
    return NULL;
  }

  public static function Login($email, $pass)
  {
    global $mysqli;

    if (Misc::MultipleEmpty($email, $pass))
      return Misc::Error('Email and/or pass empty.');

    if (!Misc::IsValidEmail($email))
      return Misc::Error('Invalid email.');

    $email = $mysqli->real_escape_string($email);

    $account = $mysqli->query(sprintf(
      'SELECT * FROM `users` WHERE `email` = "%s"',
      $email
    ))->fetch_object();

    if (empty($account))
      return Misc::Error('User not found.');

    if (md5(md5($pass) . $account->salt) != $account->password)
      return Misc::Error('Wrong password.');

    $_SESSION['loggedIn'] = true;
    $_SESSION['uid'] = $account->id;
    header('Location: ?page=index');
  }

  // return array(data, message)
  public static function Register($firstname, $lastname, $email, $pass, $confirmpass)
  {
    global $mysqli;
    if (Misc::MultipleEmpty($firstname, $lastname, $email, $pass, $confirmpass))
      return [NULL, 'All fields are required.'];

    if ($pass !== $confirmpass)
      return [NULL, 'Password confirmation failed.'];

    if (!Misc::IsValidEmail($email))
      return [NULL, 'Invalid email format.'];

    $email = $mysqli->real_escape_string($email);

    $exists = $mysqli->query(sprintf(
      'SELECT `id` FROM `users` WHERE `email` = "%s"',
      $email
    ))->fetch_object();

    if (!empty($exists))
      return [NULL, 'A user with this email already exists.'];

    $salt = Misc::GenerateRandomString(32);
    $password = md5(md5($mysqli->real_escape_string($pass)) . $salt);

    $firstname = ucfirst(strtolower($mysqli->real_escape_string($firstname)));
    $lastname = ucfirst(strtolower($mysqli->real_escape_string($lastname)));

    $query = $mysqli->query(sprintf(
      'INSERT INTO `users` (`firstname`, `lastname`, `email`, `password`, `salt`, `role`) VALUES("%s", "%s", "%s", "%s", "%s", %d)',
      $firstname,
      $lastname,
      $email,
      $password,
      $salt
    ));

    if ($query)
      return ['ok', 'User registered successfully.'];

    return [NULL, 'Something went wrong.'];
  }

  // return array(data, message)
  public static function ChangePassword($oldpass, $newpass, $confirm)
  {
    global $mysqli;

    if (Misc::MultipleEmpty($oldpass, $newpass, $confirm))
      return [NULL, 'All fields are required.'];

    if ($newpass !== $confirm)
      return [NULL, 'Password confirmation failed.'];

    if (md5(md5($oldpass) . self::getAccount()->salt) != self::getAccount()->password)
      return [NULL, 'Old password is not correct.'];

    $salt = Misc::GenerateRandomString(32);
    $query = $mysqli->query(sprintf(
      'UPDATE `users` SET `password` = "%s", `salt` = "%s" WHERE `id` = %d',
      $mysqli->real_escape_string(md5(md5($newpass) . $salt)),
      $salt,
      self::getAccount()->id
    ));

    if ($query)
      return ['ok', 'Password changed!'];

    return [NULL, 'Something went wrong.'];
  }

  // return array(data, message)
  public static function ChangeEmail($currpass, $newemail)
  {
    global $mysqli;

    if (Misc::MultipleEmpty($currpass, $newemail))
      return [NULL, 'All fields are required.'];

    if (!Misc::IsValidEmail($newemail))
      return [NULL, 'Invalid email.'];

    if (md5(md5($currpass) . self::getAccount()->salt) != self::getAccount()->password)
      return [NULL, 'Your password is not correct'];

    $newemail = $mysqli->real_escape_string($newemail);

    // Check for existing accounts with that email
    $check = $mysqli->query(sprintf(
      'SELECT `id` FROM `users` WHERE `email` = "%s"',
      $newemail
    ));

    if ($check->num_rows != 0)
      return [NULL, 'This email is used by another user. Choose something else.'];

    $query = $mysqli->query(sprintf(
      'UPDATE `users` SET `email` = "%s" WHERE `id` = %d',
      $newemail,
      self::getAccount()->id
    ));

    if ($query)
      return ['ok', 'Email changed!'];

    return [NULL, 'Something went wrong.'];
  }
}
