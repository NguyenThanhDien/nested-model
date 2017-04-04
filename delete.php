<?php
  require_once "kncsdl.php";
  $database = new databases();
  $id = $_GET['id'];
  $database->delete($id);
  header ("location: index.php");
?>
