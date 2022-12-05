<?php
require_once 'app/init.php';

function DownloadFile($content, $filename, $extension) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $filename . '.' . $extension);
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    // header('Content-Length: ' . filesize($file['name']));
    echo $content;
}

if (!isset($_GET['id']))
  die('u wot m8?');

$id = $_GET['id'];

$data = Documents::GetDocument($id);

if ($data[0] == NULL)
  die($data[1]);

DownloadFile($data[1]->document, $data[1]->filename, $data[1]->extension);
