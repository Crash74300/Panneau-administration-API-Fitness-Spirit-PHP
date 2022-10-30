<!DOCTYPE html>

<head>
  <title>Ajouter un utilisateur</title>
  <meta name="description" content="Ajout d'utilisateur">
</head>

<?php
//////////////////////////Elements qui se répète////////////////////////////////////
require_once '../elements/header.php';

///////////////////////////Forcer l'utilisateur à être connecté/////////////////////
require_once '../functions/auth.php';
forcer_utilisateur_connecte();

///////////////////////////Vérifie que l'utilisateur est bien administrateur////////////////////////////////
if ($_SESSION['perm'] == 0) {
  session_start();
  unset($_SESSION['connecte']);
  unset($_SESSION['name']);
  unset($_SESSION['perm']);
  unset($_SESSION['id']);
  header('Location: ../session/login.php');
  exit();
};

///////////////////////////////////////////Récupère les information du formulaire pour créer l'utilisateur dans la base de donnée////////////////////////////////////

if (!empty($_POST)) {
  if (
    isset($_POST["client_name"], $_POST["client_email"],  $_POST["client_description"])
    && !empty($_POST["client_name"]) && !empty($_POST["client_email"]) && !empty($_POST["client_description"])
  ) {
    $nom = strip_tags($_POST["client_name"]);
    $email = htmlspecialchars($_POST["client_email"]);
    $des = htmlspecialchars($_POST["client_description"]);
    $tel = htmlspecialchars($_POST["client_tel"]);
    $date = Date("Y-m-d");
    if (!empty($_POST["client_perm"]))
      $perm = 1;
    else
      $perm = 0;
    if (!empty($_POST["client_active"]))
      $on = 1;
    else
      $on = 0;

    require_once '../database/database.php';
    $db = Database::connect();
    $check = $db->prepare('SELECT client_email FROM utilisateurs WHERE client_email = ?');
    $check->execute(array($email));
    $data = $check->fetch();
    $row = $check->rowCount();
    if ($row == 0) {
      $sql = 'INSERT INTO utilisateurs(client_name, client_email, client_tel, client_description, client_perm, client_active, password_token, password_asked_date) VALUES (:client_name, :client_email, :client_tel, :client_description, :client_perm, :client_active, :password_token, :password_asked_date)';
      
      
///////////////////////////Création du TOKEN//////////////////////
      $string = implode('', array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9')));
      $token = substr(str_shuffle($string), 0, 20);

      $query = $db->prepare($sql);
      $query->bindValue(":client_name", $nom, PDO::PARAM_STR);
      $query->bindValue(":client_email", $email, PDO::PARAM_STR);
      $query->bindValue(":client_tel", $tel, PDO::PARAM_STR);
      $query->bindValue(":client_description", $des, PDO::PARAM_STR);
      $query->bindValue(":client_perm", $perm, PDO::PARAM_STR);
      $query->bindValue(":client_active", $on, PDO::PARAM_STR);
      $query->bindValue(":password_token", $token, PDO::PARAM_STR);
      $query->bindValue(":password_asked_date", $date, PDO::PARAM_STR);
      $query->execute();



///////////////////////////Envoie de l'EMAIL////////////////////////////////////////////

//////////////Création du lien avec le token pour modifier le mot de passe////////////////////////////////
      $link = 'http://fitnessspirit.online/session/pass.php?token=' . $token;
    //destinataire
      $to = $email; 

      // Sujet
      $subject = 'Création de votre mot de passe';

      // message
      $message = '
     <html>
      <head>
       <title>Création de votre compte Fitness Spirit</title>
      </head>
      <body>
       <p>Bienvenue chez Fitness Spirit! <br> Nous sommes heureux de vous compter parmi nos partenaires!
       <br> Pour réinitialiser votre mot de passe, veuillez suivre ce lien : <a href="' . $link . '">Définir votre mot de passe</a></p>
       
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

      echo '<script language="JavaScript" type="text/javascript">alert("L\'utilisateur à bien été créé!");</script>';
      echo '<script language="JavaScript" type="text/javascript">window.location.replace("admin.php");</script>';
    } else {
      echo '<script language="JavaScript" type="text/javascript">alert("L\'adresse email existe déjà!");</script>';
    };
  } else {
    echo "<script>alert(\"Le formulaire est incomplet\")</script>";
  };
};

?>


<!--------------------------------Structure HTML------------------------------------->

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
      <label for="nom" class="form-label text-light">Nom de la structure</label>
      <input type="Nom" name="client_name" class="form-control client_name" placeholder="">
      <br>
      <label for="email" class="form-label text-light">Adresse Email</label>
      <input type="email" name="client_email" class="form-control client_email" placeholder="name@example.com">
      <br>
      <label for="tel" class="form-label text-light">Téléphone</label>
      <input type="tel" name="client_tel" class="form-control client_tel" placeholder="0622*****9">
      <br>
      <div class="mb-3">
      <label class="form-label text-light">Description</label>
      <textarea class="form-control client_description" name="client_description" rows="3"></textarea>
    </div>
      <input class="ont" type="checkbox" checked data-toggle="toggle" data-on="Actif" data-off="Inactif" data-onstyle="success" data-offstyle="danger">
    </div>
    <h4 class="text-light">Permission d'administration</h4>
    <div>
    <input class="form-check-input client_perm" value="1" name="client_perm" id="client_perm" type="checkbox" role="switch">
    <label for="client_perm" class="form-check-label text-light">Admin</label>
    </div>
    <br>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Créer le partenaire</button>


<!------------------------- Modal ----------------------------->

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
            <p>Confirmez-vous la création du profil un email lui sera adressé à l'adresse ?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Retour</button>
            <button type="submit" class="btn btn-primary">Confirmer</button>
          </div>
        </div>
      </div>
    </div>
  </form>
 
<!----------------------------Appel des modules javascript------------------------------------------>

  <script type="text/javascript" src="../scripts/script.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>




</html>