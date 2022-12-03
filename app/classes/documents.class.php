<?php
class Documents extends CMS
{
  public static $allowed_extensions = [
    'pdf',
    'sql',
    'doc',
    'docx',
    'odt',
    'txt',
    'rtf',
    'xls',
    'xlsx',
    'zip',
    'rar',
    '7z'
  ];

  public static function GetAllDocuments()
  {
    global $mysqli;

    $documents = $mysqli->query(
      'SELECT
        `id`,
        `description`,
        `filename`,
        `extension`,
        `project_id`,
        `creation_date`
      FROM `documents` ORDER BY `id` DESC'
    );
    if ($documents->num_rows == 0)
      return NULL;

    $documentsArr = [];
    while ($document = $documents->fetch_object()) {
      // $parser->parse($document->description);
      $document->description = nl2br(htmlentities($document->description));
      $document->display_edit_button = Account::IsTutor();
      $documentsArr[] = $document;
    }

    return $documentsArr;
  }

  public static function Exists($id)
  {
    global $mysqli;

    $document = $mysqli->query(
      'SELECT `id` FROM `documents` WHERE `id` = ' . $id
    );
    if ($document->num_rows == 0)
      return false;

    return true;
  }

  public static function GetDocument($id)
  {
    global $mysqli;

    $id = intval($id);

    $fetch = $mysqli->query(sprintf('SELECT * FROM documents WHERE `id` = %d', $id));
    if ($fetch->num_rows == 0)
      return [NULL, 'Δεν βρέθηκε έγγραφο.'];

    $fetch = $fetch->fetch_object();

    return ['ok', $fetch];
  }

  public static function LoadDocument($id)
  {
    global $mysqli;

    $id = intval($id);

    $fetch = $mysqli->query(sprintf(
      'SELECT
        `id`,
        `description`,
        `filename`,
        `extension`,
        `project_id`,
        `creation_date`
      FROM documents WHERE `id` = %d',
      $id
    ));
    if ($fetch->num_rows == 0)
      return [NULL, 'Δεν βρέθηκε έγγραφο.'];

    $fetch = $fetch->fetch_object();

    return ['ok', $fetch];
  }

  public static function DeleteDocument($id)
  {
    global $mysqli;

    $id = intval($id);

    $documents = $mysqli->query(sprintf(
      'SELECT `id` FROM `documents` WHERE `id` = %d',
      $id
    ));

    if ($documents->num_rows == 0)
      return [NULL, 'Δεν υπάρχει αυτό το έγγραφο.'];

    $delete = $mysqli->query(sprintf('DELETE FROM `documents` WHERE `id` = %d', $id));
    if ($delete)
      return ['ok', 'Το έγγραφο διαγράφηκε επιτυχώς.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }

  public static function UploadDocument($doc, $description, $projectId)
  {
    global $mysqli;

    // Get filename without extension
    $filename = pathinfo($doc['name'], PATHINFO_FILENAME);
    $ext = pathinfo($doc['name'], PATHINFO_EXTENSION);

    if (!in_array($ext, self::$allowed_extensions))
      return [NULL, "Μη έγκυρος τύπος αρχείου: <b>$ext</b>"];

    $description = $mysqli->real_escape_string($description);
    $filename = $mysqli->real_escape_string($filename);
    $ext = $mysqli->real_escape_string($ext);

    if (Misc::MultipleEmpty($description, $filename, $ext))
      return [NULL, 'Όλα τα πεδία είναι υποχρεωτικά.'];

    // Check for file size
    if ($doc['size'] > MAX_FILE_SIZE)
      return [NULL, 'Το αρχείο είναι πολύ μεγάλο.'];

    // If projectId is not NULL, check if project exists
    if ($projectId !== NULL)
      if (!Projects::Exists($projectId))
        return [NULL, 'Δεν υπάρχει αυτή η εργασία.'];

    // Insert file into database
    $doc = $mysqli->real_escape_string(file_get_contents($doc['tmp_name']));

    if ($projectId === NULL) {
      $insert = $mysqli->query(sprintf(
        'INSERT INTO `documents`
          (`document`, `description`, `filename`, `extension`)
        VALUES
          ("%s", "%s", "%s", "%s")',
        $doc,
        $description,
        $filename,
        $ext
      ));
    } else {
      $insert = $mysqli->query(sprintf(
        'INSERT INTO `documents`
          (`document`, `description`, `filename`, `extension`, `project_id`)
        VALUES
          ("%s", "%s", "%s", "%s", %d)',
        $doc,
        $description,
        $filename,
        $ext,
        intval($projectId)
      ));
    }

    if ($insert)
      return ['ok', 'Το έγγραφο αποστάλθηκε επιτυχώς.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }

  public static function EditDocument($id, $update_document, $doc, $description)
  {
    global $mysqli;

    $id = intval($id);
    if (!self::Exists($id))
      return [NULL, 'Δεν υπάρχει αυτό το έγγραφο.'];

    $description = $mysqli->real_escape_string($description);

    if (empty($description))
      return [NULL, 'Η περιγραφή είναι υποχρεωτική.'];

    if ($update_document) {
      // Get filename without extension
      $filename = pathinfo($doc['name'], PATHINFO_FILENAME);
      $ext = pathinfo($doc['name'], PATHINFO_EXTENSION);

      if (!in_array($ext, self::$allowed_extensions))
        return [NULL, "Μη έγκυρος τύπος αρχείου: <b>$ext</b>"];

      $filename = $mysqli->real_escape_string($filename);
      $ext = $mysqli->real_escape_string($ext);

      if (Misc::MultipleEmpty($filename, $ext))
        return [NULL, 'Το όνομα αρχείου και η επέκταση είναι υποχρεωτικά.'];

      // Check for file size
      if ($doc['size'] > MAX_FILE_SIZE)
        return [NULL, 'Το αρχείο είναι πολύ μεγάλο.'];

      // Update file in database
      $doc = $mysqli->real_escape_string(file_get_contents($doc['tmp_name']));

      $update = $mysqli->query(sprintf(
        'UPDATE `documents` SET
          `document` = "%s",
          `description` = "%s",
          `filename` = "%s",
          `extension` = "%s"
        WHERE `id` = %d',
        $doc,
        $description,
        $filename,
        $ext,
        $id
      ));
    } else {
      $update = $mysqli->query(sprintf(
        'UPDATE `documents` SET
          `description` = "%s"
        WHERE `id` = %d',
        $description,
        $id
      ));
    }

    if ($update)
      return ['ok', 'Το έγγραφο ενημερώθηκε επιτυχώς.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }
}
