<?php
require_once 'app/init.php';
error_reporting(E_ALL);

// Account::TutorRequired();

function ShowPDF($content) {
  $file = sprintf('%s.pdf', Misc::GenerateRandomString());
  // $content = base64_decode($content);
  header('Content-type: application/pdf');
  header('Content-Disposition: attachment; filename='.$file);
  echo $content;
}

if (!isset($_GET['id']))
  die('u wot m8?');

$id = $_GET['id'];
$type = $_GET['type'];

// $data = Stores::GetDocuments($id);
$data = [];

if ($data[0] == NULL)
  die($data[1]);

ShowPDF($data[1]->document);

