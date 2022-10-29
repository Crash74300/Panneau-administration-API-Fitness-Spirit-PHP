<?php
require_once '../elements/header.php';
require '../database/database.php';
$db = Database::connect();
$sql = "UPDATE salles SET salles_confirm = '1' WHERE salles_id =" . $_GET["id"];
$query = $db->prepare($sql);
$query->execute();
echo '<script language="JavaScript" type="text/javascript">alert("La salle à bien été confirmé!");</script>';
echo '<script language="JavaScript" type="text/javascript">window.location.replace("dashboard.php");</script>';
?>
<script type="text/javascript" src="../scripts/script.js"></script>