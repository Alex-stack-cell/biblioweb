<?php
require('config.php');
session_start();
if (empty($_SESSION['login'])){
	//utilisateur non connecté
	header('location:liste.php?err=7');
	exit;
}else if (isset($_POST['btPhotoAjouter']) && !empty($_FILES)){
	//var_dump($_POST);
	//var_dump($_FILES);
	//check si jpg ou png
	if(empty ($user_ref)) {
		$user_ref = $_SESSION['id'];
	}
	 
	$fichier = array_pop($_FILES);
	$filename = $fichier['name'];
	$newPath = 'IMG/profiles/'.$filename;
	
	
	if ($fichier['error']==0){		//pas d’erreur d’envoi
		if ($fichier['size']<500000){
			if ($fichier['type']!='image/jpeg'){
				//si pas jpeg, il faut convertir
				$uploaded = imagejpeg(imagecreatefromstring(file_get_contents($fichier['tmp_name'])),$newPath);
			} else {
				//si jpeg il faut juste déplacer
				$uploaded = move_uploaded_file ( $fichier['tmp_name'] , $newPath);
			}
			if ($uploaded){
				$uploaded = imagecreatefromjpeg($newPath);
				//redimmensionner
				$newImage = imagecreatetruecolor(150, 150);
				imagecopyresampled($newImage, $uploaded, 0, 0, 0, 0, 150, 150, imagesx($uploaded) , imagesy($uploaded) );
				//verified text v bottom right
				imagestring ( $newImage , 5 , 150-30 , 150-30 , 'V' ,0X00FF00 );
				//upload
				imagejpeg($newImage,$newPath);
				
				//modifier la base de données et ajouter le nom de la photo
				//requete sql
				$link = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);
				if ($link){
					mysqli_query($link, "SET NAMES utf8");
					$query = "update users set photo_profil='$filename'  WHERE id= $user_ref ";
					$result = mysqli_query($link, $query);
					if(!$result) {
						//erreur lors de la requete
						$msg = 'Votre fichier n\'a pas pu être ajouté dans la base de données.';
					}
					mysqli_close($link);
				} else {
					//erreur de connexion à la db
					header('location:liste.php?err=0');
					exit;
				}
				
			} else {
				$msg = 'Votre fichier n\'a pas pu être ajouté.';
			}
		}
	}
}
/////////////////////////////////////////////////////
//récupérer les informations à afficher
$link = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);
if ($link){
	mysqli_query($link, "SET NAMES utf8");
	$query = "SELECT * FROM users WHERE id=".$_SESSION['id'];
	$result = mysqli_query($link, $query);
	if($result) {
		$userInfo = mysqli_fetch_assoc($result);
		mysqli_free_result($result);
	} else {
		//erreur lors de la requete
		var_dump(mysqli_error($link));
		exit;
		header('location:liste.php?err=1');
		exit;
	}
	mysqli_close($link);
} else {
	//erreur de connexion à la db
	header('location:liste.php?err=0');
	exit;
}
//var_dump($userInfo);
?>
<!DOCTYPE html>
<html>

<head>
    <style>
    form {
        margin: 5px;
        border: dashed 1px black;
        padding: 10px;
        width: 50%;
    }
    </style>
</head>

<body>
    <a href="liste.php">Retour</a>
    <h1>Bienvenue <?= $userInfo['login'] ?>, voici vos données personnelles</h1>

    <div id="photo">
        <?php if (!empty($userInfo['photo_profil']) ) {?>
        <img src="images/profiles/<?= $userInfo['photo_profil'] ?>" alt="profile pic" style="height:100px">


        <?php } else { ?>

        <p>Vous n'avez pas de photo de profil</p>
        <?php } ?>

        <form method="post" action="<?= $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
            <p>Modifier ma photo</p>
            <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
            <input type="file" id="myFile" name="filename">

            <button type="submit" id="btPhotoAjouter" name="btPhotoAjouter">Ajouter cette photo</button>
        </form>
        <?= isset($msg) ? $msg : '' ?>
    </div>

    <div>
        <p><strong>Login:</strong><?= $userInfo['login'] ?></p>
        <p><strong>Email:</strong><?= $userInfo['email'] ?></p>
        <p><strong>Statut:</strong><?= $userInfo['statut'] ?></p>
        <p><strong>Membre depuis:</strong><?= date("d-m-Y",strtotime($userInfo['created_at'])) ?></p>
    </div>
    <?php //require('derniereConsult.php'); ?>
</body>

</html>