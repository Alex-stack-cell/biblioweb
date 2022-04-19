<?php 
session_start();
require'config.php';
require 'secure.php';

// Sécurisation d'accès : membre ?
if(empty($_SESSION['login'])) {
	header('Location:index.php');
	header('Statut:302');
}

// Sécurisation d'accès : Admin ?
if($_SESSION['statut']!="admin") {
	header('Location:index.php');
	header('Statut:302');
}

$user_date_subscription = (int)date("Y",strtotime($_SESSION['created_at']));
$current_year = (int) date("Y",time());
define("LIMIT",3);

// Sécurisation d'accès : Ancienneté d'au moins 3 ans ?
if($current_year-$user_date_subscription<LIMIT){
	header('Location:index.php');
	header('Statut:302');
}

require 'includes/header.php'?>
<?php 
// Initialisation des variables
@$link = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);
$message = '';
$books = null;
$finish = false;

// Afficher les livres au hasards jusqu'à ce qu'il n'y ait plus de livre sans photo de couverture !
// Pour modifier la photo de couverture => envoyer via formulaire une photo

// Vérifier si l'utilisateur à cliquer sur le btn d'envoie
if(isset($_POST['btSend'])) {
	
	// Vérifier si la référence du livre existe
	if(!empty($_POST['ref'])){ 
		$ref = $_POST['ref'];
		
		//Vérifier si le fichier n'est pas vide
		if(strlen($_FILES['cover']['name'])!==0) {
			$file = $_FILES['cover'];
			$file_name = $file['name'];

			// Vérifier si le fichier est bien envoyé
			if($file['error']==0) {
				
				// Vérifier le format de fichier
				$file_extension = $file['type'];
				$extensions = ['image/jpeg','image/jpg','image/gif','image/bmp'];
				if(in_array($file_extension,$extensions)){
					$source = $file['tmp_name'];
					$destination = getcwd().'/IMG/'.$file_name;
					
					//Vérifie si le fichier a bien été téléversé dans la bonne destination
					if(@move_uploaded_file($source,$destination)) {
						
						// Mettre à jour la base de donnée
						if($link) {
							$cover = mysqli_real_escape_string($link,$file_name);
							$ref = mysqli_real_escape_string($link,$ref);
							$query = 
							"UPDATE `books` 
							 SET cover_url = '$cover'
							 WHERE ref = $ref ";
							$result = mysqli_query($link,$query);

							if($result && mysqli_affected_rows($link)>0) {
								$finish = true;
								$books['cover_url'] = $cover;
								
								// Récupération des données 
								$books['ref'] = $_POST['ref'];
								$books['title'] = $_POST['title'];
								$books['lastname'] = $_POST['lastname'];
								$books['firstname'] = $_POST['firstname'];
								$message ="Succès, l'image à bien été modifiée !";
							} else {
								$message = "Echec ! Une erreur est survenue lors de l'envoie de votre requête ";
							}
						}
					} else {
						$message = "Echec ! Le dossier ".$destination." est inexistant";
					}
				} else {
					$message = "Echec ! Format de fichier invalide ! ";
				}
			} else {
				$message = "Echec ! Une erreur est survenue lors du téléversement";
			}
		} else {
			$message = "Echec ! Veuillez fournir une photo de couverture, svp";
		}
	} else {
		$message = "Ce livre possède une photo de couverture.\n Veuillez réessayer";
	}
} else { // Afficher aléatoirement les livres sans couverture
	if(!mysqli_connect_error()) {
		$query = 
		"SELECT ref, title, lastname, firstname 
		FROM `books`
		JOIN authors ON books.author_id = authors.id
		WHERE cover_url IS NULL 
		ORDER BY RAND() LIMIT 1"
		;
		$result = mysqli_query($link,$query);
		if($result && mysqli_affected_rows($link)>0) {
			$books = mysqli_fetch_assoc($result);
			$title = $books['title'];
			$ref = $books['ref']; 
			$lastname = $books['lastname']; 
			$firstname = $books['firstname']; 
		}
	} else {
		$message = 'Echec lors de la connection à la base de données !';
	}
}
if($link) {
	mysqli_close($link);
}
?>

<!--1) Afficher les info de livre au hasard
	n'ayant pas de photos de couvertures => DONE
-->
<!--2) Admin peut ajouter une photo de couverture-->
<!--3) Valider le format de l'image => DONE-->
<!--4) Après download il peut afficher le prochain livre DONE -->
<!--5) Sécuriser l'accès de la page => Admin seulement et inscrit depuis plus de 3 ans-->
<?php if(!empty($books)) {?>
<h2><?= $books['title']?></h2>
<p>de <?php echo "{$books['firstname']} {$books['lastname']}" ?></p>
<?php if(!$finish) {?>
<form enctype="multipart/form-data" method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
    <input type="hidden" name="FILE_MAX_SIZE" value="400000">
    <input type="file" name="cover">
    <input type="hidden" name="ref" value="<?=$ref?>">
    <input type="hidden" name="title" value="<?=$title?>">
    <input type="hidden" name="lastname" value="<?=$lastname?>">
    <input type="hidden" name="firstname" value="<?=$firstname?>">
    <button name="btSend">Charger une couverture</button>
</form>
<?php } else {?>
<img src="<?php echo "IMG/".$cover?>" alt="<?php echo $cover ?>">
<p class="alert alert-success"><?= $message?></p>
<a class="btn btn-primary" href="<?= $_SERVER['PHP_SELF'] ?>">Afficher le prochain livre</a>
<?php }?>
<?php } else {?>
<p>Aucun livre sans couverture.</p>
<?php }?>

<?php require'includes/footer.php'?>