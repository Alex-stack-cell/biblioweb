<nav>
    <ul style="display: flex; flex-direction: row; justify-content: space-around;">
        <?php if(empty($_SESSION['login'])) { ?>
        <li><a href="signin.php">Se connecter</a></li>
        <li><a href="signin.php">S'inscrire</a></li>
        <?php } else { ?>
        <li><a href="signin.php?logout">Se déconnecter</a></li>
        <li><a href="authors.php">Auteurs</a></li>
        <li><a href="book_cover.php">Book cover</a></li>
        <?php } ?>
    </ul>
</nav>