<!DOCTYPE html>
<?php
require_once '../elements/header.php';
require '../database/database.php';
require_once '../functions/auth.php';
forcer_utilisateur_connecte();
$db = Database::connect();
$id = $_SESSION['id'];
$name = $_SESSION['name'];

$sql = "SELECT * FROM utilisateurs WHERE client_id='" . $id . "'";
$request = $db->prepare($sql);
$request = $db->query($sql);
$profil = $request->fetch();
$perm = $profil["client_perm"];
$active = $profil["client_active"];

if ($perm == 0) {
  $perm = 'Utilisateur';
} else {
  $perm = 'Administrateur';
};

/*Recherche des salles*/
$statement = $db->query("SELECT* FROM salles WHERE salles_prop =" . $profil["client_id"]);
?>
<html>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="#">Bonjour</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse " id="navbarText">
      <ul class="navbar-nav justify-content-between ">

        <li class="nav-item">
          <a class="nav-link" href="../session/logout.php">Déconnexion</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <h1 class="text-light">Bienvenue sur votre espace client <?= $profil["client_name"] ?></h1>
        <br>
        <h4 class="text-light">Adresse email: <?= $profil["client_email"] ?></h4>
      </div>
      <div class="col-12 mt-3">
        <div class="row">
          <p class="text-light">Description de votre profil: <?= $profil["client_description"] ?></p>
        </div>
        <h4 class="text-light">Votre téléphone: <?= $profil["client_tel"] ?></h4>
        <h4 class="text-light">La permission de votre profil: <?= $perm ?></h4>



        <?php
        if ($active == 1) {
          echo '<div class="form-check form-switch">';
          echo '<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDisabled" disabled>';
          echo '<label class="form-check-label text-light" for="flexSwitchCheckCheckedDisabled">Votre compte est désactivé!</label>';
          echo '</div>';
        } else {
          echo '<div class="form-check form-switch">';
          echo '<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckCheckedDisabled" checked disabled>';
          echo '<label class="form-check-label text-light" for="flexSwitchCheckCheckedDisabled">Votre compte est activé!</label>';
          echo '</div>';
        };
        echo '<div class="row ligne ">';
        ?>
      </div>
      <?php
      while ($salles = $statement->fetch()) {
        $name = $salles['salles_name'];
        $adresse = $salles['salles_adresse'];
        echo '<div class="row ">';
        echo '<div class="col-lg-5 col-md-6 col-sm-12 ">';
        echo '<div class="card">';
        echo '<div class="row">';

        echo '<h5 class="">' . $salles['salles_name'] . '</h5>';
        echo '<p> ' . $salles['salles_tel'] . '</p>';
        echo '<p> ' . $salles['salles_adresse'] . '</p>';

      ?>
        <a class="btn btn-primary" href="/user/salles_vue.php?id=<?php echo $salles["salles_id"] ?>">Voir</a>
      <?php

        echo '</div>';
        echo '</div>';
        echo '</div>';
      }
      echo '</div>';

      ?>


    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>