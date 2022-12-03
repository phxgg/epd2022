<?php
class Announcements extends CMS
{
  public static function GetAllAnnouncements()
  {
    global $mysqli;

    $announcements = $mysqli->query(
      'SELECT
        `id`,
        `title`,
        `body`,
        `is_project`,
        `projectId`,
        `creation_date`
      FROM `announcements` ORDER BY `id` DESC'
    );
    if ($announcements->num_rows == 0)
      return NULL;
    
    $announcementsArr = [];
    while ($announcement = $announcements->fetch_object()) {
      // $parser->parse($announcement->body);
      $announcement->body = nl2br(htmlentities($announcement->body));
      $announcement->display_edit_button = Account::IsTutor();
      $announcementsArr[] = $announcement;
    }

    return $announcementsArr;
  }

  public static function Fetch($id)
  {
    global $mysqli;

    $id = intval($id);

    $data = $mysqli->query(sprintf(
      'SELECT
        `id`,
        `title`,
        `body`,
        `is_project`,
        `projectId`,
        `creation_date`
      FROM `announcements`
      WHERE `id` = %d',
      $id
    ));

    if (!$data || $data->num_rows == 0)
      return NULL;

    return $data->fetch_object();
  }

  // return array(data, message)
  public static function AddAnnouncement($title, $body, $isproject, $projectId)
  {
    global $mysqli;

    $isproject = intval($isproject);
    if ($isproject != 0 && $isproject != 1) $isproject = 0;

    if (Misc::MultipleEmpty($title, $body))
      return [NULL, 'Όλα τα πεδία είναι υποχρεωτικά.'];

    $title = $mysqli->real_escape_string($title);
    $body = $mysqli->real_escape_string($body);

    if ($isproject == 1 && $projectId !== NULL) {
      $projectId = intval($projectId);
      $insert = $mysqli->query(sprintf(
        'INSERT INTO `announcements` (`title`, `body`, `is_project`, `projectId`) VALUES ("%s", "%s", %d, %d)',
        $title,
        $body,
        $isproject,
        $projectId
      ));
    } else {
      $insert = $mysqli->query(sprintf(
        'INSERT INTO `announcements` (`title`, `body`, `is_project`) VALUES ("%s", "%s", %d)',
        $title,
        $body,
        $isproject
      ));
    }
    
    if ($insert)
      return ['ok', 'Η ανακοίνωση δημιουργήθηκε επιτυχώς.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }

  public static function EditAnnouncement($id, $title, $body)
  {
    global $mysqli;

    $id = intval($id);
    // $isproject = intval($isproject);
    // if ($isproject != 0 && $isproject != 1) $isproject = 0;

    if (Misc::MultipleEmpty($title, $body))
      return [NULL, 'Όλα τα πεδία είναι υποχρεωτικά.'];

    // Get current user info
    $fetch = Announcements::Fetch($id);
    if (empty($fetch))
      return [NULL, 'Δεν βρέθηκε ανακοίνωση.'];

    $title = $mysqli->real_escape_string($title);
    $body = $mysqli->real_escape_string($body);

    $query = $mysqli->query(sprintf(
      'UPDATE `announcements`
      SET
        `title` = "%s",
        `body` = "%s"
      WHERE `id` = %d',
      $title,
      $body,
      // $isproject,
      $id
    ));
    
    if ($query)
      return ['ok', 'Η ανακοίνωση ενημερώθηκε επιτυχώς.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }

  public static function DeleteAnnouncement($id)
  {
    global $mysqli;

    $fetch = Announcements::Fetch($id);
    if (empty($fetch))
      return [NULL, 'Δεν βρέθηκε ανακοίνωση.'];
    
    $id = intval($id);

    $query = $mysqli->query(sprintf('DELETE FROM `announcements` WHERE `id` = %d', $id));

    if ($query)
      return ['ok', 'Η ανακοίνωση διαγράφηκε επιτυχώς.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }
}
