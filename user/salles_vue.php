<!DOCTYPE html>
<?php
require_once '../elements/header.php';
require '../database/database.php';
require_once '../functions/auth.php';

forcer_utilisateur_connecte();
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
      <ul class="navbar-nav justify-content-between ">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Retour</a>
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
        <h2><?= $salles["salles_name"] ?></h2>
        <br>
        <h4>Adresse email: <?= $salles["salles_email"] ?></h4>
        <br>
        <h4>Adresse postale: <?= $salles["salles_adresse"] ?></h4>
        <br>
        <?php if ($confirm == 0) { ?>
          <a class="btn btn-primary" href="confirm.php?id=<?php echo $salles["salles_id"] ?>">Confirmer la création de la salles?</a>
        <?php
        }
        ?>

      </div>
      <div class="col-lg-6 col-10">
        <div class="row">
          <p>Description: <?= $salles["salles_description"] ?></p>
        </div>
        <h4>Téléphone: <?= $salles["salles_tel"] ?></h4>
        <h4>Cette salle est <?= $compte ?></h4>
        <br>
        <br>
        <div class="container border">
          <h1>Vos services</h1>
          <?php
          if ($confirm == 1) {
          if ($salles['salles_active'] == 0) {
          if ($planning == 1) { ?>
            <div class="container">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckCheckedDisabled" checked disabled>
                <label class="form-check-label" for="flexSwitchCheckCheckedDisabled">Gestion de planning</label>
              </div>
              <a class="btn btn-primary" href="/admin/salles.php?name=">Gérer le planning de la salle</a>
            </div>
          <?php
          } else { ?>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="switch" disabled>
              <label class="form-check-label">Gestion de planning</label>
            </div>
          <?php
          };

          if ($materiels == 1) { ?>
            <div class="container">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckCheckedDisabled" checked disabled>
                <label class="form-check-label" for="flexSwitchCheckDisabled">Commande de matèriel</label>
              </div>
              <a class="btn btn-primary" href="/admin/salles.php?name=">Commander du matériel</a>
            </div>
          <?php
          } else { ?>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckCheckedDisabled" disabled>
              <label class="form-check-label" for="flexSwitchCheckDisabled">Commande de matèriel</label>
            </div>
          <?php
          };
          if ($boissons == 1) { ?>
            <div class="container">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDisabled" checked disabled>
                <label class="form-check-label" for="flexSwitchCheckDisabled">Commande de boissons</label>
              </div>
              <a class="btn btn-primary" href="/admin/salles.php?name=">Commander des boissons</a>
            </div>
          <?php
          } else { ?>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDisabled" disabled>
              <label class="form-check-label" for="flexSwitchCheckDisabled">Commande de boissons</label>
            </div>
        </div>
      <?php
          }}else {
            echo '<h5 class="">Cette salle doit être activé pour accéder aux fonctionnalités!</h5>';
          }}else {
            echo '<h5 class="">Vous devez confirmer la création de la salle pour débloquer les fonctionnalités!</h5>';
          }
      ?>

      </div>
    </div>
  </div>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>