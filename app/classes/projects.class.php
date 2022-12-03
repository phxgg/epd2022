<?php
class Projects extends CMS
{
  public static function Exists($id)
  {
    global $mysqli;
    $id = intval($id);

    $project = $mysqli->query(sprintf(
      'SELECT `id` FROM `projects` WHERE `id` = %d',
      $id
    ));

    return $project->num_rows > 0;
  }

  public static function GetAllProjects()
  {
    global $mysqli;

    $projects = $mysqli->query(
      'SELECT
        `id`,
        `title`,
        `body`,
        `deadline_date`,
        `creation_date`
      FROM `projects` ORDER BY `id` DESC'
    );
    if ($projects->num_rows == 0)
      return NULL;

    $projectsArr = [];
    while ($project = $projects->fetch_object()) {
      $document = self::GetDocument($project->id);

      $project->body = nl2br(htmlentities($project->body));
      $project->display_edit_button = Account::IsTutor();
      $project->document = $document;
      $projectsArr[] = $project;
    }

    return $projectsArr;
  }

  public static function GetDocument($projectId)
  {
    global $mysqli;

    $projectId = intval($projectId);

    $fetch = $mysqli->query(sprintf(
      'SELECT
        `id`,
        `description`,
        `filename`,
        `extension`,
        `project_id`,
        `creation_date`
      FROM documents WHERE `project_id` = %d',
      $projectId
    ));
    if ($fetch->num_rows == 0)
      return [NULL, 'Δεν υπάρχουν έγγραφα.'];

    $fetch = $fetch->fetch_object();

    return ['ok', $fetch];
  }

  public static function AddProject($title, $body, $deadline, $doc)
  {
    global $mysqli;

    $title = $mysqli->real_escape_string($title);
    $body = $mysqli->real_escape_string($body);
    $deadline = $mysqli->real_escape_string($deadline);

    if (Misc::MultipleEmpty($title, $body, $deadline))
      return [NULL, 'Παρακαλώ συμπληρώστε όλα τα πεδία.'];

    $insert = $mysqli->query(sprintf(
      'INSERT INTO `projects` (`title`, `body`, `deadline_date`) VALUES ("%s", "%s", "%s")',
      $title,
      $body,
      $deadline
    ));

    if (!$insert)
      return [NULL, 'Προέκυψε κάποιο σφάλμα.'];

    $inserted_project = $mysqli->query('SELECT `id` FROM `projects` WHERE `id` = LAST_INSERT_ID()');
    $inserted_project = $inserted_project->fetch_object();

    $announcementData = Announcements::AddAnnouncement(
      sprintf('Ανακοίνωση εργασίας %d', $inserted_project->id),
      'Έχει ανακοινωθεί μια νέα εργασία με τίτλο: ' . $title . '. Παρακαλώ ελέγξτε την σελίδα των εργασιών για περισσότερες πληροφορίες.',
      1,
      $inserted_project->id
    );

    if ($announcementData[0] == NULL)
      return [NULL, 'Προέκυψε σφάλμα κατά την προσθήκη ανακοίνωσης της εργασίας.'];

    $docData = Documents::UploadDocument(
      $doc,
      sprintf('Εκφώνηση εργασίας %d', $inserted_project->id),
      $inserted_project->id
    );

    if ($docData[0] == NULL)
      return [NULL, 'Προέκυψε σφάλμα κατά το ανέβασμα του έγγραφου.'];

    return ['ok', 'Η εργασία προστέθηκε επιτυχώς.'];
  }

  public static function DeleteProject($id)
  {
    global $mysqli;

    $id = intval($id);

    if (!self::Exists($id))
      return [NULL, 'Η εργασία δεν υπάρχει.'];

    $delete = $mysqli->query(sprintf(
      'DELETE FROM `projects` WHERE `id` = %d',
      $id
    ));

    if (!$delete)
      return [NULL, 'Προέκυψε κάποιο σφάλμα.'];

    $mysqli->query(sprintf(
      'DELETE FROM `documents` WHERE `project_id` = %d',
      $id
    ));

    $mysqli->query(sprintf(
      'DELETE FROM `announcements` WHERE `projectId` = %d',
      $id
    ));

    return ['ok', 'Η εργασία διαγράφηκε επιτυχώς.'];
  }
}
