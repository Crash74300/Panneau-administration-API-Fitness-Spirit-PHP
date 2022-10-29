<!DOCTYPE html>
<?php


//////////////////////Eléments du header qui se répète///////////////////////////////
require_once '../elements/header.php';


////////////////////////Force l'utilisateur à être connecté////////////////////////////////////////
require_once '../functions/auth.php';
forcer_utilisateur_connecte();


//////////////////Bloc l'accès d'un utilisateur qui n'est pas administrateur//////////////////////////////////////////////
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
<html>

<head>
  <title>FS Panel Admin</title>
  <link rel="stylesheet" href="../style/style.css">
  <meta name="description" content="Page d'administration">
</head>

<body>
    
    
    <!------------------------------Menu de navigation----------------------------------->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="#">Bonjour Admin</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav mr-auto ">
        <li class="nav-item active">
          <a class="nav-link" href="insert.php">+ Ajouter un partenaire</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../session/logout.php">Déconnexion</a>
        </li>
      </ul>
    </div>
  </nav>
  <h1>Liste des partenaires</h1>
<?php


   /////////////////////////////Connection à la base de donnée/////////////////////////////////////
  require '../database/database.php';
  $db = Database::connect();
  
  
  //////////////////////////////PAGINATION///////////////////////////////////////////////////////
  $currentPage = (int)($_GET['page'] ?? 1);
  if ($currentPage <= 0) {
    throw new Exception('numéro de page invalide');
  }
  $count = (int)$db->query('SELECT COUNT(client_id) FROM utilisateurs')->fetch(PDO::FETCH_NUM)[0];
  $perPage = 12;
  $pages = ceil($count / $perPage);
  if ($currentPage > $pages) {
    ('Cette page n\'existe pas');
  }
  $offset = $perPage * ($currentPage - 1);
  $link = 'admin.php';
  
  
  ///////////////////////////////////////Récupération de la base de donnée et création de la page////////////////////////////////////////////////////////
  $statement = $db->query("SELECT * FROM utilisateurs ORDER BY client_id DESC LIMIT $perPage OFFSET $offset");


  while ($user = $statement->fetch()) {
    $des = $user['client_description'];
    $active = $user['client_active'];

    echo '<div  id="card" class="container mt-3 border border-info row">';
    //echo '<div class="row">';
    echo '<div class="left col-6 p-3">';
    echo '<div class="image"></div>';

    if ($active == 1) {
      echo '<input class="on p-3" type="checkbox"  data-toggle="toggle" data-on="Actif" data-off="Inactif" data-onstyle="success" data-offstyle="danger"></div>';
    } else {
      echo '<input class="off" type="checkbox" checked data-toggle="toggle" data-on="Actif" data-off="Inactif" data-onstyle="success" data-offstyle="danger"></div>';
    }

    echo '<div class="col-5 m-3">';
    echo '<h2 class="name">' . $user['client_name'] . '</h2>';
    echo '<p class="id"> id client = ' . $user['client_id'] . '</p>';

?>
    <a class="btn btn-primary" href="/admin/profil.php?id=<?php echo $user["client_id"] ?>">Voir</a>
<?php
    if ($user['client_password'] == '') {
      echo '<h2>En attente de mot de passe</h2>';
    };
   // echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
  
  
///////////////Déconnection de la base de donnée//////////////////////////////
  Database::disconnect();
?>


<!---------------------------Boutons de la pagination--------------------------------------->
  <div class=" d-flex justify-content-between m-4 js-filter-pagination">
    <?php if ($currentPage > 1) : ?>
      <a href="<?= $link ?>?page=<?= $currentPage - 1 ?>" class="btn btn-primary">&laquo; Page précédent</a>
    <?php endif ?>
    <?php if ($currentPage === 1) : ?>
      <a href="" class="btn btn-secondary">&laquo; Page précédent</a>
    <?php endif ?>
    <?php if ($currentPage < $pages) : ?>
      <a href="<?= $link ?>?page=<?= $currentPage + 1 ?>" class="btn btn-primary ml-auto">Page suivante &raquo;</a>
    <?php endif ?>
  </div>

<!----------------------Chargement des modules javascript--------------------------------------------->


  <script type="module" src="../scripts/app.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>