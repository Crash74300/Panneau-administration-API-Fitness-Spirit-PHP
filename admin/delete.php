<!DOCTYPE html>
<?php
require_once '../elements/header.php';
require_once '../functions/auth.php';

forcer_utilisateur_connecte();
if ($_SESSION['perm'] == 0) {
  session_start();
  unset($_SESSION['connecte']);
  unset($_SESSION['name']);
  unset($_SESSION['perm']);
  unset($_SESSION['id']);
  header('Location: ../session/login.php');
  exit();
};

?>