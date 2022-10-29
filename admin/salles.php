<!DOCTYPE html>
<?php
require_once '../elements/header.php';
require '../database/database.php';
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
$db = Database::connect();
$sql = "SELECT * FROM salles WHERE salles_id =" . $_GET["id"];
$request = $db->prepare($sql);
$request = $db->query($sql);
$salles = $request->fetch();
$planning = $salles['salles_planning'];
$materiels = $salles['salles_materiels'];
$boissons = $salles['salles_boissons'];
$confirm = $salles['salles_confirm'];
$compte = '';

if ($salles['salles_active'] == 0) {
  $compte = 'Activé';
} else {
  $compte = 'Désactivé';
}

?>
<html>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark 
  <?php
  if ($salles['salles_active'] == 0) {
  ?>
  bg-success">
  <?php
  } else {
  ?>
    bg-danger">
  <?php
  }
  ?>
  <a class="navbar-brand" href="#">Bonjour</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse " id="navbarText">
    <ul class="navbar-nav mr-auto ">
      <li class="nav-item">
        <a class="nav-link" href="admin.php">Acceuil</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../session/logout.php">Déconnexion</a>
      </li>
    </ul>
  </div>
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-10">
        <h2 class="text-light"><?= $salles["salles_name"] ?></h2>
        <br>
        <h4 class="text-light">Adresse email: <?= $salles["salles_email"] ?></h4>
        <br>
        <h4 class="text-light">Adresse postale: <?= $salles["salles_adresse"] ?></h4>
      </div>

      <div class="col-lg-6 col-10">
        <div class="row d-flex justify-content-center">
          <p class="text-light">Description: <?= $salles["salles_description"] ?></p>
        </div>
        <h4 class="text-light">Téléphone: <?= $salles["salles_tel"] ?></h4>


      </div>
    </div>
    <?php
    if ($salles['salles_active'] == 0) {
    ?>
      <div class="border border-danger p-2">
        <h4 class="text-light">Cette salle est <?= $compte ?></h4>
        <a class="btn btn-primary" href="etat.php?id=<?php echo $salles["salles_id"] ?>">Active</a>
        <label class="form-check-label text-light">Appuyer pour désactiver la salle</label>
      </div>
    <?php
    } else {
    ?>
      <h4 class="text-light">Cette salle est <?= $compte ?></h4>
      <a class="btn btn-danger" href="etat.php?id=<?php echo $salles["salles_id"] ?>">Inactive</a>
      <label class="form-check-label text-light">Appuyer pour activer la salle</label>
    <?php
    }if ($confirm == 0) {
      echo '<h5 class="mt-4 text-light">Cette salle n\'a pas été validé par l\'utilisateur</h5>';
    }
    ?>

    <br>

    <div class="container border border-info mt-4 p-2">
      <h1 class="text-light">Vos services</h1>


      <div class="container d-flex flex-column">
        <div class="mt-4">
          <?php
          if ($salles['salles_planning'] == 1) {
          ?>
            <a class="btn btn-primary" href="planning.php?id=<?php echo $salles["salles_id"] ?>">Activé</a>

          <?php
          } else {
          ?>
            <a class="btn btn-danger" href="planning.php?id=<?php echo $salles["salles_id"] ?>">Inactif</a>
          <?php
          }
          ?>
          <label class="form-check-label text-light">Gestion du planning</label>
        </div>

        <div class="mt-4">
          <?php
          if ($salles['salles_materiels'] == 1) {
          ?>
            <a class="btn btn-primary" href="mat.php?id=<?php echo $salles["salles_id"] ?>">Activé</a>

          <?php
          } else {
          ?>
            <a class="btn btn-danger" href="mat.php?id=<?php echo $salles["salles_id"] ?>">Inactif</a>
          <?php
          }
          ?>
          <label class="form-check-label text-light">Commande de matériel</label>
        </div>
        <div class="mt-4">
          <?php
          if ($salles['salles_boissons'] == 1) {
          ?>
            <a class="btn btn-primary" href="boissons.php?id=<?php echo $salles["salles_id"] ?>">Activé</a>

          <?php
          } else {
          ?>
            <a class="btn btn-danger" href="boissons.php?id=<?php echo $salles["salles_id"] ?>">Inactif</a>
          <?php
          }
          ?>
          <label class="form-check-label text-light">Commande de boissons</label>
        </div>
      </div>

      <script type="text/javascript" src="../scripts/script.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>