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
      header('Location: '.BASE_URL.'/?page=index');
      die();
    }
  }

  public static function LoginRequired()
  {
    if (!self::IsLoggedIn()) {
      header('Location: '.BASE_URL.'/?page=login');
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
      header('Location: '.BASE_URL.'/?page=index');
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
      return Misc::Error('To email ή/και ο κωδικός είναι κενός.');

    if (!Misc::IsValidEmail($email))
      return Misc::Error('Το email δεν είναι έγκυρο.');

    $email = $mysqli->real_escape_string($email);

    $account = $mysqli->query(sprintf(
      'SELECT * FROM `users` WHERE `email` = "%s"',
      $email
    ))->fetch_object();

    if (empty($account))
      return Misc::Error('Δεν βρέθηκε λογαριασμός με αυτό το email.');

    if (md5(md5($pass) . $account->salt) != $account->password)
      return Misc::Error('Λάθος κωδικός.');

    $_SESSION['loggedIn'] = true;
    $_SESSION['uid'] = $account->id;
    header('Location: '.BASE_URL.'/?page=index');
  }

  // return array(data, message)
  public static function Register($firstname, $lastname, $email, $pass, $confirmpass)
  {
    global $mysqli;
    if (Misc::MultipleEmpty($firstname, $lastname, $email, $pass, $confirmpass))
      return [NULL, 'Όλα τα πεδία είναι υποχρεωτικά.'];

    if ($pass !== $confirmpass)
      return [NULL, 'Οι κωδικοί δεν ταιριάζουν.'];

    if (!Misc::IsValidEmail($email))
      return [NULL, 'Το email δεν είναι έγκυρο.'];

    $email = $mysqli->real_escape_string($email);

    $exists = $mysqli->query(sprintf(
      'SELECT `id` FROM `users` WHERE `email` = "%s"',
      $email
    ))->fetch_object();

    if (!empty($exists))
      return [NULL, 'Υπάρχει ήδη λογαριασμός με αυτό το email.'];

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
      return ['ok', 'Επιτυχής εγγραφή.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }

  // return array(data, message)
  public static function ChangePassword($oldpass, $newpass, $confirm)
  {
    global $mysqli;

    if (Misc::MultipleEmpty($oldpass, $newpass, $confirm))
      return [NULL, 'Όλα τα πεδία είναι υποχρεωτικά.'];

    if ($newpass !== $confirm)
      return [NULL, 'Οι κωδικοί δεν ταιριάζουν.'];

    if (md5(md5($oldpass) . self::getAccount()->salt) != self::getAccount()->password)
      return [NULL, 'Λάθος κωδικός.'];

    $salt = Misc::GenerateRandomString(32);
    $query = $mysqli->query(sprintf(
      'UPDATE `users` SET `password` = "%s", `salt` = "%s" WHERE `id` = %d',
      $mysqli->real_escape_string(md5(md5($newpass) . $salt)),
      $salt,
      self::getAccount()->id
    ));

    if ($query)
      return ['ok', 'Επιτυχής αλλαγή κωδικού.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }

  // return array(data, message)
  public static function ChangeEmail($currpass, $newemail)
  {
    global $mysqli;

    if (Misc::MultipleEmpty($currpass, $newemail))
      return [NULL, 'Όλα τα πεδία είναι υποχρεωτικά.'];

    if (!Misc::IsValidEmail($newemail))
      return [NULL, 'Το email δεν είναι έγκυρο.'];

    if (md5(md5($currpass) . self::getAccount()->salt) != self::getAccount()->password)
      return [NULL, 'Λάθος κωδικός.'];

    $newemail = $mysqli->real_escape_string($newemail);

    // Check for existing accounts with that email
    $check = $mysqli->query(sprintf(
      'SELECT `id` FROM `users` WHERE `email` = "%s"',
      $newemail
    ));

    if ($check->num_rows != 0)
      return [NULL, 'Υπάρχει ήδη λογαριασμός με αυτό το email.'];

    $query = $mysqli->query(sprintf(
      'UPDATE `users` SET `email` = "%s" WHERE `id` = %d',
      $newemail,
      self::getAccount()->id
    ));

    if ($query)
      return ['ok', 'Επιτυχής αλλαγή email.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }
}
