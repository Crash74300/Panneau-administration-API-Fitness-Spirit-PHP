<!DOCTYPE html>
<?php

require_once '../elements/header.php';
require_once '../functions/auth.php';
require '../database/database.php';
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
if (!empty($_POST)) {

  if (
    isset($_POST["salles_name"], $_POST["salles_adresse"], $_POST["salles_tel"], $_POST["salles_description"])
    && !empty($_POST["salles_name"]) && !empty($_POST["salles_adresse"]) && !empty($_POST["salles_tel"]) && !empty($_POST["salles_description"])
  ) {
    $nom = strip_tags($_POST["salles_name"]);
    $tel = strip_tags($_POST["salles_tel"]);
    $adresse = htmlspecialchars($_POST["salles_adresse"]);
    $des = htmlspecialchars($_POST["salles_description"]);

    $sql = "SELECT client_id, client_email FROM utilisateurs WHERE client_id =" . $_GET["id"];
    $request = $db->prepare($sql);
    $request = $db->query($sql);
    $profil = $request->fetch();
    $prop = $profil["client_id"];
    $email = $profil["client_email"];
    if (!empty($_POST["salles_planning"])) {
      $planning = 1;
    } else {
      $planning = 0;
    };
    if (!empty($_POST["salles_boissons"])) {
      $boissons = 1;
    } else {
      $boissons = 0;
    };
    if (!empty($_POST["salles_materiels"])) {
      $materiels = 1;
    } else {
      $materiels = 0;
    };
    if (!empty($_POST["salles_acitve"])) {
      $active = 1;
    } else {
      $active = 0;
    };
    require_once '../database/database.php';
    $db = Database::connect();
    $check = $db->prepare('SELECT salles_name FROM salles WHERE salles_name = ?');
    $check->execute(array($nom));
    $data = $check->fetch();
    $row = $check->rowCount();
    if ($row == 0) {
      $sql = 'INSERT INTO salles(salles_name, salles_adresse, salles_planning, salles_boissons, salles_materiels, salles_tel, salles_prop, salles_active, salles_description) VALUES (:salles_name, :salles_adresse, :salles_planning, :salles_boissons, :salles_materiels, :salles_tel, :salles_prop, :salles_active, :salles_description)';

      $query = $db->prepare($sql);
      $query->bindValue(":salles_name", $nom, PDO::PARAM_STR);
      $query->bindValue(":salles_adresse", $adresse, PDO::PARAM_STR);
      $query->bindValue(":salles_description", $des, PDO::PARAM_STR);
      $query->bindValue(":salles_planning", $planning, PDO::PARAM_STR);
      $query->bindValue(":salles_boissons", $boissons, PDO::PARAM_STR);
      $query->bindValue(":salles_materiels", $materiels, PDO::PARAM_STR);
      $query->bindValue(":salles_tel", $tel, PDO::PARAM_STR);
      $query->bindValue(":salles_prop", $prop, PDO::PARAM_STR);
      $query->bindValue(":salles_active", $active, PDO::PARAM_STR);
      $query->execute();



      /****************EMAIL*******************/
      /****************EMAIL*******************/

      // Destinataires
      $to = $email; // notez la virgule

      // Sujet
      $subject = 'Création d\'une salle dans votre espace client';

      // message
      $message = '
     <html>
      <head>
       <title>Création d\'une salle dans votre espace client</title>
      </head>
      <body>
       <p>Bonjour"' . $client_name . '"! <br> Nous sommes heureux de voir que vous vous développez et nous vous accompagnons dans la gestion de vos salles!
       <br> C\'est pourquoi nous venons d\'ajouter à votre espace client votre nouvelle salle! <br> Vous pouvez à présent géré vos application depuis votre espace dédié!</p>
       
      </body>
     </html>
     ';

      // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
      $headers[] = 'MIME-Version: 1.0';
      $headers[] = 'Content-type: text/html; charset=utf-8';

      // En-têtes additionnels
      $headers[] = 'From: FitnessSpirit <fitness-spirit@fs.fr>';


      // Envoi
      mail($to, $subject, $message, implode("\r\n", $headers));


      echo '<script language="JavaScript" type="text/javascript">alert("La salle à bien été créé!");</script>';
      echo '<script language="JavaScript" type="text/javascript">window.location.replace("admin.php");</script>';
    } else {
      echo '<script language="JavaScript" type="text/javascript">alert("Le nom de la salle est déjà utilisé!");</script>';
    };
  } else {
    echo "<script>alert(\"Le formulaire est incomplet\")</script>";
  };
};
?>
<html>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Bonjour Admin</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="../admin/admin.php">Retour</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../session/logout.php">Déconnexion</a>
        </li>
      </ul>
    </div>
  </nav>



  <form method="post" class="container">
    <div class="mb-3">
      <label for="nom" class="form-label text-light">Nom de la salle: </label>
      <input type="Nom" name="salles_name" class="form-control salles_name" placeholder="">
      <br>
      <label for="adresse" class="form-label text-light">Adresse postale: </label>
      <input type="adresse" name="salles_adresse" class="form-control salles_adresse" placeholder="123 rue du bourd 78500 LA RILLETTE">
      <br>
      <br>
      <label for="tel" class="form-label text-light">Téléphone: </label>
      <input type="tel" name="salles_tel" class="form-control salles_tel" placeholder="0627....66">
      <br>
      <label for="email" class="form-label text-light">Email: </label>
      <input type="email" name="salles_email" class="form-control salles_tel" placeholder="sal....@...l.com">
      <br>
      <input class="active" name="salles_active" type="checkbox" checked data-toggle="toggle" data-on="Actif" data-off="Inactif" data-onstyle="success" data-offstyle="danger">
    </div>

    <h4 class="text-light">Permission de base</h4>
    <input class="form-check-input salles_boissons" name="salles_boissons" type="checkbox" role="switch" checked>
    <label class="form-check-label text-light">Vente de boissons</label>
    <input class="form-check-input salles_materiels" name="salles_materiels" type="checkbox" role="switch" checked>
    <label class="form-check-label text-light">Achat de matériel</label>
    <h4 class="text-light">Permission optionnelles</h4>
    <input class="form-check-input salles_planning" name="salles_planning" type="checkbox" role="switch">
    <label class="form-check-label text-light">Gestion du planning</label>
    <div class="mb-3">
      <label class="form-label text-light">Description</label>
      <textarea class="form-control salles_description" name="salles_description" rows="3"></textarea>
    </div>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Créer la salle</button>


    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Confirmez-vous la création de la salle ?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Retour</button>
            <button type="submit" class="btn btn-primary">Confirmer</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <script type="text/javascript" src="../scripts/script.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>




</html>