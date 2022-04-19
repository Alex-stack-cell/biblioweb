<?php
session_start();
require 'config.php';

$message = "";
$alert="";
$livres = [];
$livre = null;
$auteursNom = [] ;
$auteursPrenom = [];
$infosRecherche = [];


$link = @mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);
@mysqli_set_charset($link,'utf-8');

// Supprimer un livre

if(isset($_POST['btDelete'])) {
    $ref_book = (int)$_POST['delete_ref'];
    $ref_book = mysqli_real_escape_string($link,$ref_book);
    
    if($link) {
        $query = 
        "DELETE FROM `books` 
         WHERE ref = $ref_book 
        ";
        $result=mysqli_query($link,$query);
        
        if($result && mysqli_affected_rows($link)>0){
            echo "<p class='alert alert-success'>Livre supprimé</p>";
        }
    }
}

if(isset($_POST['addBook'])) {
    if(!empty($_POST['new_author']) && !empty($_POST['new_book']) && !empty($_POST['new_description'])) {
        $new_author = (int) htmlentities($_POST['new_author']);
        $new_book = htmlentities($_POST['new_book']);
        $new_description = htmlentities($_POST['new_description']);
        
        if(gettype($new_author) === "integer") {
             //var_dump($new_author, $new_book);
            if($link) {
                $new_author = mysqli_real_escape_string($link,$new_author);
                $new_book = mysqli_real_escape_string($link,$new_book);
                $new_description = mysqli_real_escape_string($link,$new_description);
                $query = 
                "INSERT INTO books (title, author_id, description, cover_url)
                VALUES ('$new_book', '$new_author', '$new_description', 'NULL')";
                $result = mysqli_query($link,$query);

                var_dump($query);
                if($result && mysqli_affected_rows($link)>0) {
                    echo "<p class='alert alert-success'>Livre ajouté avec succès</p>";
                }            
            }
        }
       
    }
} 

if(!mysqli_connect_error()) {
	
	if(isset($_GET['btSearch'])) {
		if(!empty($_GET['auteur'] && $_GET['livre'])) {
			$auteur = $_GET['auteur'];
			$livre = $_GET['livre'];
			$rechercher = "SELECT ref, title, firstname, lastname, description, cover_url
			FROM books, authors
			WHERE books.author_id = authors.id
			AND title ='$livre'
			AND lastname = '$auteur'
			OR title ='$livre'
			AND firstname = '$auteur'
			;";
			$resultRecherche = mysqli_query($link,$rechercher);
			
			if($resultRecherche) {
				while($infoRecherche = mysqli_fetch_assoc($resultRecherche)) {
					$infosRecherche [] = $infoRecherche;
				}
				
				if(sizeof($infosRecherche)>=1) {
					$recherche = $infosRecherche[0];
				} $alert = "Le livre et/ou l'auteur est mal orthographié";
				
				mysqli_free_result($resultRecherche);
			
			} 
			
		} else $alert = "Veuillez spécifier l'auteur et le titre du livre, svp";
		
	} else {
			$alert = "Recherchez un livre";
	}
	
	// Afficher les livres
	$query = "SELECT ref, title, firstname, lastname, description, cover_url
			FROM books, authors
			WHERE books.author_id = authors.id
			ORDER BY ref ASC 
			;";
	$result = mysqli_query($link,$query);

	if($result) {
		while($livre = mysqli_fetch_assoc($result)) {
			array_push($livres,$livre);
		}
	$tableauInfos = mysqli_fetch_fields($result);
	mysqli_free_result($result);
	
	} else $message = "Echec lors de l'envoie de la requête.";	

} else  {

	$message = "Echec de connexion à la base de données. Code d'erreur: ".mysqli_connect_errno();
}

@mysqli_close($link);

$filename = 'presets.json';
$json = file_get_contents($filename);
if(file_exists($filename)){
	$stylesheet = json_decode($json,true);
	$homestyles = $stylesheet['homestyles'];
} 
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width = viewport-width, initial-scale = 1.0">
    <meta name="author" content="A.Gavriilidis">
    <meta name="description" content="Application de recherches de livres">
    <title>Biblioweb : Liste</title>
    <!--BOOTSTRAP V5.0-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <!--GOOGLE FONT-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Gentium+Book+Basic:wght@400;700&display=swap" rel="stylesheet">
    <style>
    <?php foreach($homestyles as $selector=> $rules) {
        echo "{$selector} {\n";

        foreach ($rules as $rule) {
            echo "{$rule};\t\n";
        }

        echo "}\t\n";
    }

    ?>input[type=number] {
        -moz-appearance: textfield;
        /*Pour mozilla seulement*/
    }
    </style>
</head>

<body>
    <div>
        <section class="container">
            <form id="rechercher" method="get" action="<?php echo $_SERVER['PHP_SELF']?>" style="padding: 10px;">
                <fieldset id="containerFrm">
                    <legend>Chercher un livre</legend>
                    <label for="auteur">Auteur:</label>
                    <input type="text" id="auteur" name="auteur">
                    <label for="livre">Livre:</label>
                    <input type="text" id="livre" name="livre">
                    <button name="btSearch">Rechercher</button>
                    <button type="reset">Réinitialiser</button>
                </fieldset>
                <p><?php echo $alert ?></p>
            </form>
            <form id="ajouter" method="POST" action="<?php echo $_SERVER['PHP_SELF']?>" style="padding: 10px;">
                <fieldset id="containerFrm">
                    <legend>Ajouter un livre</legend>
                    <label for="new_author">ID auteur:</label>
                    <input type="number" id="new_author" name="new_author">
                    <label for="new_book">Livre:</label>
                    <input type="text" id="new_book" name="new_book">
                    <label for="new_description">Description:</label>
                    <input type="text" id="new_description" name="new_description">
                    <button name="addBook">Ajouter</button>
                </fieldset>
            </form>
        </section>
        <section class="container">
            <table border="2">
                <thead>
                    <tr>
                        <?php if(@$tableauInfos):?>
                        <?php for($i = 0; $i<sizeof($tableauInfos); $i++):?>
                        <th><?= ucfirst($tableauInfos[$i]->name);?></th>
                        <?php endfor ; ?>
                        <?php endif?>
                        <th>Actions</th>
                    <tr>
                </thead>
                <tbody>
                    <!--Si l'utilisateur fait une recherche-->
                    <?php if(!empty($recherche)):?>
                    <tr>
                        <td><?php $recherche['ref'];?></td>
                        <td><?php echo $recherche['title']?></td>
                        <td><?php echo $recherche['firstname']?></td>
                        <td><?php echo $recherche['lastname']?></td>
                        <td><?php echo substr($recherche['description'],0,50)."...";?></td>
                        <td><img src="<?php echo "biblioweb-covers/".$recherche['cover_url']?>" width="50" height="80">
                        </td>
                    </tr>
                    <?php else :?>
                    <!--Sinon : -->
                    <?php foreach($livres as $livre):?>
                    <tr>
                        <td><?php echo $livre['ref'];?></td>
                        <td class="book-title"><?php echo $livre['title']?></td>
                        <td class="author"><?php echo $livre['firstname']?></td>
                        <td class="author"><?php echo $livre['lastname']?></td>
                        <td><?php echo substr($livre['description'],0,50)."..."; ?></td>
                        <td><img src="<?php echo "biblioweb-covers/".$livre['cover_url']?>" width="50" height="80"></td>
                        <td>
                            <form method="post" action="edit.php">
                                <input type="hidden" name="refLivre" value="<?php echo $livre['ref']?>">
                                <button name="btEdit">&#9998;</button>
                            </form>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
                                <input type="hidden" name="delete_ref" value="<?=$livre['ref']?>">
                                <button name="btDelete">&#9747;</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach ; ?>
                    <?php endif ; ?>
                </tbody>
            </table>
        </section>
        <section class="container">
            <p><?=$message ;?></p>
        </section>
</body>

</html>