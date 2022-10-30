<!DOCTYPE html>

<?php
require_once '../elements/header.php';
require '../database/database.php';
$db = Database::connect();
if (empty($_GET['token'])) {  // Si aucun token n'est spécifié en paramètre de l'URL
  echo 'Aucun token n\'a été spécifié';
  exit;
};
$msg = "";
// On récupère les informations par rapport au token dans la base de données
$query = $db->prepare('SELECT password_asked_date FROM utilisateurs WHERE password_token = ?');
$query->bindValue(1, $_GET['token']);
$query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);

if (empty($row)) {  // Si aucune info associée au token n'est trouvé
  echo 'Ce token n\'a pas été trouvé';
  exit;
}

// On calcul la date de la génération du token + 1 day
$tokenDate = strtotime('+1 day', strtotime($row['password_asked_date']));
$todayDate = Date("Y-m-d");

if ($tokenDate < $todayDate) {  // Si la date est dépassé le délais de 3hrs
  echo 'Token expiré !';
  exit;
}

if (!empty($_POST)) {  // Si le formulaire a été soumis
  if (!empty($_POST['client_password']) && !empty($_POST['client_pass'])) {  // Si le formulaire est correctement remplit
    if ($_POST['client_password'] === $_POST['client_pass']) {  // Si les deux mots de passes sont les mêmes
      // On hash le mot de passe
      $password = sha1($_POST['client_password']);

      // On modifie les informations dans la base de données
      $query = $db->prepare('UPDATE utilisateurs SET  client_password = ?, password_token = "" WHERE password_token = ?');
      $query->bindValue(1, $password);
      $query->bindValue(2, $_GET['token']);
      $query->execute();

      echo '<script language="JavaScript" type="text/javascript">alert("Votre mot de passe à bien été modifié!");</script>';
      echo '<script language="JavaScript" type="text/javascript">window.location.replace("login.php");</script>';
    } else {  // si les deux mots de passe ne sont pas identiques
      $msg = '<div style="color: red;">Les deux mots de passes ne sont pas identiques.</div>';
    }
  } else {
    $msg = '<div style="color: red;">Veuillez remplir tous les champs du formulaire.</div>';
  }
};
?>
<?php echo $msg ?>

<form method="post" class="container">
  <div class="mb-3">
    <label for="client_password" class="form-label">Mot de passe</label>
    <input type="password" name="client_password" class="form-control client_password" placeholder="">
    <br>
    <label for="client_pass" class="form-label">confirmation du mot de passe</label>
    <input type="password" name="client_pass" class="form-control client_pass" placeholder="">
    <br>
    <button type="submit" class="btn btn-primary">Enregistrer mon mot de passe</button>
</form>