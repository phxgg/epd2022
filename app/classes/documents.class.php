<?php
class Documents extends CMS
{
  public static function LoadAllDocuments()
  {
    global $mysqli;

    $documents = $mysqli->query('SELECT * FROM `documents`');

    if ($documents->num_rows == 0)
      return [NULL, 'Δεν βρέθηκαν έγγραφα.'];
    
    $documents = $documents->fetch_object();

    return ['ok', $documents];
  }

  public static function GetDocument($id)
  {
    global $mysqli;

    $id = intval($id);

    $fetch = $mysqli->query(sprintf('SELECT * FROM documents WHERE `id` = %d', $id));
    if ($fetch->num_rows == 0)
      return [NULL, 'Δεν υπάρχει έγγραφο.'];

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

  public static function UploadDocument($doc, $description)
  {
    global $mysqli;

    // Check for file extension
    $docExt = end(explode('.', $doc['name']));

    if ($docExt !== 'pdf')
      return [NULL, 'Το αρχείο δεν είναι έγκυρο.'];

    // Check for file size
    $max_file_size = 1048576;
    if ($doc['size'] > $max_file_size) // 1mb = 1048576 bytes
      return [NULL, 'Το αρχείο είναι πολύ μεγάλο.'];

    // Insert file into database
    $doc = $mysqli->real_escape_string(file_get_contents($doc['tmp_name']));

    $insert = $mysqli->query(sprintf(
      'INSERT INTO `documents`
        (`document`, `description`)
      VALUES
        ("%s", "%s")',
      $doc,
      $description
    ));

    if ($insert)
      return ['ok', 'Το έγγραφο αποστάλθηκε επιτυχώς.'];
      
    return [NULL, 'Κάτι πήγε στραβά.'];
  }
}
