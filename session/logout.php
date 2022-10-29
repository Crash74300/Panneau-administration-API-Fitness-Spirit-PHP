<?php
session_start();
unset($_SESSION['connecte']);
unset($_SESSION['name']);
unset($_SESSION['perm']);
unset($_SESSION['id']);
header('Location: login.php');
