<!DOCTYPE html>
<?php
require_once '../elements/header.php';


/////////////////////////Forcer l'utilisateur à être connecté///////////////////////////
require_once '../functions/auth.php';
forcer_utilisateur_connecte();


///////////////////////////////////Vérifie que l'utilisateur est administrateur////////////////////////
if ($_SESSION['perm'] == 0) {
  session_start();
  unset($_SESSION['connecte']);
  unset($_SESSION['name']);
  unset($_SESSION['perm']);
  unset($_SESSION['id']);
  header('Location: ../session/login.php');
  exit();
};


/////////////////////////////Connexion à la base de données//////////////////////////////////////////
require '../database/database.php';
$db = Database::connect();
$sql = "SELECT * FROM utilisateurs WHERE client_id =" . $_GET["id"];
$request = $db->prepare($sql);
$request = $db->query($sql);
$profil = $request->fetch();
$perm = $profil["client_perm"];
if ($perm == 0) {
  $perm = 'Utilisateur';
} else {
  $perm = 'Administrateur';
};
?>

<!--------------------------Structure HTML-------------------------------->
<html>

<body>
    
  <nav class="navbar navbar-expand navbar-dark bg-primary">
    <a class="navbar-brand" href="#">Fitness Spirit</a>
    <ul class="navbar-nav justify-content-between ">
      <li class="nav-item">
        <a class="nav-link" href="admin.php">Accueil</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="../admin/admin.php">Retour</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../session/logout.php">Déconnexion</a>
      </li>
    </ul>
  </nav>
  
    <div class="container d-flex justify-content-center">
      <div class="p-3 container">
        <h2 class="text-info"><?= $profil["client_name"] ?></h2>
        <br>
        <h4 class="text-light">Adresse postale: <?= $profil["client_adresse"] ?></h4>
        <br>
        <h4 class="text-light">Téléphone: <?= $profil["client_tel"] ?></h4>
        <br>
        <h4 class="text-light">Adresse email: <?= $profil["client_email"] ?></h4>
        <div class="mt-2">
          <p class="text-light"><?= $profil["client_description"] ?></p>
        </div>
        <h4 class="text-light">Permission: <?= $perm ?></h4>
        <h4 class="text-light">Identifiant du client: <?= $profil["client_id"] ?></h4>
        <h4 class="text-light">Etat du compte: <?= $profil["client_active"] ?></h4>
        <a class="btn btn-primary" href="/admin/insert_salle.php?id=<?php echo $profil["client_id"] ?>">Ajouter une salle</a>
      </div>
    </div>
  <?php

  $bdd = "SELECT * FROM salles WHERE salles_prop =" . $profil["client_id"];
  $statement = $db->query($bdd);



  while ($salles = $statement->fetch()) {
    if ($salles['salles_active'] == 0) {
      $compte = 'Activé';
    } else {
      $compte = 'Désactivé';
    }
    $name = $salles['salles_name'];
    $adresse = $salles['salles_adresse'];
    echo '<div class="row m-2">';
    echo '<div class="col-lg-5 col-md-6 col-sm-12">';
    echo '<div class="card">';
    echo '<div class="row">';
    echo '<h5 class=""> Salle = ' . $name . '</h5>';
    echo '<h5 class=""> Adresse = ' . $adresse . '</h5>';
    echo '<p> Téléphone = ' . $salles['salles_tel'] . '</p>';
    echo '<h4>Cette salle est ' . $compte . '</h4>'
  ?>
    <a class="btn btn-primary" href="/admin/salles.php?id=<?php echo $salles["salles_id"] ?>">Voir</a>
  <?php
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
  echo '</div>';
  ?>
</body>

</html>