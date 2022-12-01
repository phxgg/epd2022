<?php
error_reporting(E_ALL); // Disable error reporting for production.

date_default_timezone_set('Europe/Athens');

if (!defined('ACCESS'))
  define('ACCESS', true);

session_start();

define('VERSION', '1.0.0.0'); // Current version

require_once 'configuration.php';

require_once 'classes/pages.class.php';
require_once 'classes/cms.class.php';
require_once 'classes/database.class.php';
require_once 'classes/status-codes.class.php';
require_once 'classes/misc.class.php';
require_once 'classes/account.class.php';
require_once 'classes/users.class.php';
require_once 'classes/announcements.class.php';

// Log server side errors.
function LogError($e)
{
  file_put_contents('errors.txt', file_get_contents('errors.txt') . "\n" . "[" . $e->getFile() . " @ " . $e->getLine() . "] ERROR: " . $e->getMessage() . "\n");
}

// This could happen if the user was deleted from the database while being logged in.
// In case this happens, destroy the session.
if (Account::IsLoggedIn()) {
  if (!Account::getAccount()) {
    session_destroy();
    header('Location: /?page=index');
    die();
  }
}