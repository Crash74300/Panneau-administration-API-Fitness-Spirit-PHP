<?php
require_once '../elements/header.php';
require '../database/database.php';
$db = Database::connect();
$active = "SELECT salles_planning FROM salles WHERE salles_id =" . $_GET["id"];
$request = $db->prepare($active);
$request = $db->query($active);
$salles = $request->fetch();

if ($salles['salles_planning'] == 0) {
  $sql = "UPDATE salles SET salles_planning = '1' WHERE salles_id =" . $_GET["id"];
  $query = $db->prepare($sql);
  $query->execute();
  echo '<script language="JavaScript" type="text/javascript">alert("La gestion du planning à bien été activé!");</script>';
  echo '<script language="JavaScript" type="text/javascript">window.history.back();</script>';
} else {
  $sql = "UPDATE salles SET salles_planning = '0' WHERE salles_id =" . $_GET["id"];
  $query = $db->prepare($sql);
  $query->execute();
  echo '<script language="JavaScript" type="text/javascript">alert("La gestion du planning à bien été désactivé!");</script>';
  echo '<script language="JavaScript" type="text/javascript">window.history.back();</script>';
}
?>
<script type="text/javascript" src="../scripts/script.js"></script>