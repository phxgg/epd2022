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
        `creation_date`
      FROM `announcements` ORDER BY `id` DESC'
    );
    if ($announcements->num_rows == 0)
      return NULL;
    
    $announcementsArr = [];
    while ($announcement = $announcements->fetch_object()) {
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
  public static function AddAnnouncement($title, $body, $isproject)
  {
    global $mysqli;

    $isproject = intval($isproject);
    if ($isproject != 0 && $isproject != 1) $isproject = 0;

    if (Misc::MultipleEmpty($title, $body))
      return [NULL, 'All fields are required.'];

    $title = $mysqli->real_escape_string($title);
    $body = $mysqli->real_escape_string($body);

    $query = $mysqli->query(sprintf(
      'INSERT INTO `announcements` (`title`, `body`, `is_project`) VALUES("%s", "%s", %d)',
      $title,
      $body,
      $isproject
    ));
    
    if ($query)
      return ['ok', 'Announcement added successfully'];

    return [NULL, 'Something went wrong.'];
  }

  public static function EditAnnouncement($id, $title, $body, $isproject)
  {
    global $mysqli;

    $id = intval($id);
    $isproject = intval($isproject);
    if ($isproject != 0 && $isproject != 1) $isproject = 0;

    if (Misc::MultipleEmpty($title, $body))
      return [NULL, 'All fields are required.'];

    // Get current user info
    $fetch = Announcements::Fetch($id);
    if (empty($fetch))
      return [NULL, 'Announcement not found.'];

    $title = $mysqli->real_escape_string($title);
    $body = $mysqli->real_escape_string($body);

    $query = $mysqli->query(sprintf(
      'UPDATE `announcements`
      SET
        `title` = "%s",
        `body` = "%s",
        `is_project` = %d
      WHERE `id` = %d',
      $title,
      $body,
      $isproject,
      $id
    ));
    
    if ($query)
      return ['ok', 'Announcement edited successfully'];

    return [NULL, 'Something went wrong.'];
  }

  public static function DeleteAnnouncement($id)
  {
    global $mysqli;

    $fetch = Announcements::Fetch($id);
    if (empty($fetch))
      return [NULL, 'This announcement does not exist.'];
    
    $id = intval($id);

    $query = $mysqli->query(sprintf('DELETE FROM `announcements` WHERE `id` = %d', $id));

    if ($query)
      return ['ok', 'Deleted announcement!'];

    return [NULL, 'Something went wrong.'];
  }
}
