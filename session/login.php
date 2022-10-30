<?php
session_start();
require_once '../elements/header.php';
require_once '../database/database.php';

$db = Database::connect();

$erreur = null;
if (isset($_POST['send'])) {
  if (!empty($_POST['client_email']) && !empty($_POST['client_password'])) {
    $pseudo = htmlspecialchars($_POST['client_email']);
    $mdp = sha1($_POST['client_password']);
    $user = $db->prepare('SELECT * FROM utilisateurs WHERE client_email = ? AND client_password = ?');
    $user->execute(array($pseudo, $mdp));
    $profil = $user->fetch();
    $active = $profil["client_active"];
    if ($active == 1) {
      echo '<script language="JavaScript" type="text/javascript">alert("Votre comte est désactivé, merci de contacter un administrateur!");</script>';
      echo '<script language="JavaScript" type="text/javascript">window.location.replace("../session/login.php");</script>';
      exit();
    }


    if ($user->rowCount() > 0) {
      $perm = $profil["client_perm"];
      $_SESSION['connecte'] = 1;
      $_SESSION['name'] = $profil['client_name'];
      $_SESSION['id'] = $profil['client_id'];
      $_SESSION['perm'] = $profil['client_perm'];

      if ($perm == 1) {

        header('Location: ../admin/admin.php');
      } else {

        header('Location: ../user/dashboard.php');
      }
    } else {
      $erreur = "Identifiant ou mot de passe incorrect";
    };
  } else {
    $erreur = "Tout les champs ne sont pas remplit";
  }
};


if ($erreur) : ?>
  <div class="alert alert-danger">
    <?= $erreur ?>
  </div>
<?php endif ?>


<body>
  <!--Background-->
  <section class="vh-100  ">

    <div class="container py-5 h-100">
      <div class="row d-flex align-items-center justify-content-center h-100 w-100">
        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
          <h1 class="text-center text-light">Fitness spirit</h1>
          <form action="" method="post">
            <!-- Email input -->
            <div class="form-outline mb-4">
              <input type="text" class="form-control form-control-lg" name="client_email" />
              <label class="form-label text-light">Adresse email</label>
            </div>

            <!-- Password input -->
            <div class="form-outline mb-4">
              <input type="password" class="form-control form-control-lg" name="client_password" />
              <label class="form-label text-light">Mot de passe</label>
            </div>

            <div class="d-flex justify-content-around align-items-center mb-3">

           <!--   <a href="#!">Mot de passe oublié?</a>-->
            </div>

            <!-- Submit button -->
            <div class="d-flex justify-content-center pb-2">
              <button type="submit" class="btn btn-primary btn-lg btn-block" name="send">Se connecter</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </section>

</body>

</html>