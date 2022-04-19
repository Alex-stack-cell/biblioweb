<?php
session_start();

//Récupérer les notifications
$message = $_SESSION['message'] ?? '';
// var_dump($_SESSION);
require 'includes/header.php';
?>
<div class="row">
    <div class="col">
        <h1>Biblioweb</h1>
        <div id="notification">
            <p><?= $message; ?></p>
        </div>
        <nav>
            <ul>
                <li><a href="liste.php">Liste des livres</a></li>
                <li><a href="insert.php">Ajouter un livre</a></li>
                <li><a href="profil.php">Voir son profil</a></li>
                <li><a href="signin.php">Se connecter / S'inscrire</a></li>
            </ul>
        </nav>
    </div><!-- col -->
</div><!-- row -->
<?php
require 'includes/footer.php';
?>