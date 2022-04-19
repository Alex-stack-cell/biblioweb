<?php
require 'config.php';

session_start();

$books = [];
$title = "";

//Traitement des commandes
	//Recherche
if(!empty($_GET['title'])) {
	$title = $_GET['title'];
}

	//Ajout dans les favoris
if(isset($_POST['btFav'])) {
	if(!empty($_POST['ref']) && !empty($_POST['title'])) {	//Validation
		//Sauver dans la session
		$trouve = false;
		
		if(isset($_SESSION['favoris'])) {
			foreach($_SESSION['favoris'] as $favori) {
				if($_POST['ref']==$favori['ref']) {
					$trouve = true;
				}
			}
		}
		
		if(!$trouve) {
			$_SESSION['favoris'][] = [ 
				'ref' => $_POST['ref'] , 
				'title' => $_POST['title'],
				'cover_url' => $_POST['cover_url'],
			];
		}
	}
}
//Supprimer les favoris

if(isset($_POST['btDeleteFav'])) {
	$_SESSION['favoris'] = []; 
	//unset($_SESSION['favoris']);
}
$link = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);	//var_dump($link);

mysqli_query($link, "SET NAMES utf8");

if(!empty($title)) {
	$title = mysqli_real_escape_string($link, $title);
	$query = "SELECT ref,title,concat(firstname,' ',lastname) as author,description,cover_url
		FROM books JOIN authors ON author_id=authors.id WHERE title='$title'";
} else {
	$query = "SELECT ref,title,concat(firstname,' ',lastname) as author,description,cover_url 
		FROM books JOIN authors ON author_id=authors.id";
}

$result = mysqli_query($link, $query);	//var_dump($result);

if($result) {
	while(($book = mysqli_fetch_assoc($result))) {
		$books[] = $book;
	}	//var_dump($books);
	
	$fields = mysqli_fetch_fields($result);	//var_dump($fields);
	
	mysqli_free_result($result);
}

mysqli_close($link);

//Gestion des styles dynamiques
$filename = 'presets.json';
$styles = [];

if(file_exists($filename)) {
	$content = file_get_contents($filename);
	$json = json_decode($content,true);
	$styles = $json['homestyles'];		//echo '<pre>';var_dump($styles);echo '</pre>';
}
?>
<!doctype html>
<html lang="fr">

<head>
    <title>DB Access</title>
    <meta charset="utf-8">
    <style>
    <?php foreach($styles as $selector=> $rules) {
        echo "$selector {\n";

        foreach($rules as $rule) {
            echo "\t$rule;\n";
        }

        echo "}\n";
    }

    ?>table {
        margin: 15px 20px;
        border: 1px solid black;
        /* border-collapse: collapse; */
    }

    td,
    th {
        border: 1px solid silver;
    }

    thead tr {
        background-color: silver;
    }

    tfoot tr {
        background-color: lightblue;
    }

    tr:nth-child(2n) {
        background-color: silver;
    }

    tfoot {
        text-align: center;
    }

    figure {
        float: left;
        width: 150px;
        border: 1px solid orange;
        background-color: lightyellow;
        padding: 5px;
    }

    figure+p {
        clear: both;
    }
    </style>
</head>

<body>
    <!-- Afficher les favoris -->
    <?php if(!empty($_SESSION['favoris'])): ?>
    <form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
        <button name="btDeleteFav">Supprimer les favoris</button>
    </form>
    <?php foreach($_SESSION['favoris'] as $favori): ?>
    <figure>
        <img src="<?= IMG_FOLDER."covers/".$favori['cover_url'] ?>" alt="<?= $favori['title'] ?>" width="80">
        <p><?= "{$favori['ref']} - {$favori['title']}" ?></p>
    </figure>
    <?php endforeach; ?>
    <?php endif; ?>
    <p><a href="signin.php">Se connecter</a></p>
    <ul>
        <li><a href="<?= $_SERVER['PHP_SELF']; ?>">Tous</a></li>
        <li><a href="?title=Ubik">Ubik</a></li>
        <li><a href="?title=Germinal">Germinal</a></li>
    </ul>

    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
        <div>
            <label>Titre</label>
            <input type="text" name="title">
        </div>
        <button>Rechercher</button>
    </form>


    <table>
        <caption>Liste des livres</caption>
        <thead>
            <tr>
                <?php foreach($fields as $field) : ?>
                <th><?= ucfirst($field->name); ?></th>
                <?php endforeach; ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($books as $book) : ?>
            <tr>
                <td><?= $book['ref'] ?></td>
                <td><a class="book-title" href="show.php?ref=<?= $book['ref'] ?>"><?= $book['title'] ?></a></td>
                <td class="author"><?= $book['author'] ?></td>
                <td><?= substr($book['description'],0,20)."..." ?></td>
                <td><?php if(!empty($book['cover_url'])) : ?>
                    <img src="<?= IMG_FOLDER."covers/".$book['cover_url'] ?>" alt="<?= $book['title'] ?>" height="80">
                    <?php endif; ?>
                </td>
                <td>
                    <form action="delete.php" method="post">
                        <input type="hidden" name="method" value="DELETE">
                        <input type="hidden" name="ref" value="<?= $book['ref'] ?>">
                        <button class="ico-delete">&#9986;</button>
                    </form>
                    <span class="ico-edit">&#9998;</span>
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                        <input type="hidden" name="ref" value="<?= $book['ref'] ?>">
                        <input type="hidden" name="title" value="<?= $book['title'] ?>">
                        <input type="hidden" name="cover_url" value="<?= $book['cover_url'] ?>">
                        <button name="btFav">Favori</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">&copy; EPFC &dot; 2021</td>
            </tr>
        </tfoot>
    </table>

</body>

</html>