<?php
$message = '';
if(isset($_POST['btSend'])) {
	if($_FILES['couverture']['error']==0) {
		if($_FILES['couverture']['size']<50000) {
			if($_FILES['couverture']['type']=='image/jpeg') {
				$source=$_FILES['couverture']['tmp_name'];
				$destination=getcwd().'/IMG/'.basename($_FILES['couverture']['name']);
				
				if(move_uploaded_file($source,$destination)){
					$message='Votre photo a été envoyé avec succès';
				
				} else $message ='Echec d\'envoie du fichier';
			}
		} else $message='La taille du fichier dépasse la limite autorisée';
		
	} else $message='Erreur lors de l\'envoie';
	
	/*echo "<pre>";
	var_dump($_FILES);
	echo "</pre>";
	*/
}

?>
<!DOCTYPE html>
<html lang='fr'>
<head>
	<meta charset='utf-8'>
	<meta name ='viewport' content ='width = viewport-width, initial-scale=1.0'>
	<title>Chargement</title>
	<style>
		.container {
			display:flex;
			flex-direction:column;
			padding:1.8rem;
			align-items:flex-start;
		}
		input {
			padding:0.2rem;
		}
	</style>
</head>
<body>
<form enctype='multipart/form-data' method='post'action='<?=$_SERVER['PHP_SELF']?>'>
	<fieldset class="container">
		<legend>Upload</legend>
		<input type='hidden' name='MAX_FILE_SIZE' value="400000">
		<label for='photo'>Sélectionner un fichier:</label>
		<input type='file' name='couverture' id='photo'>
		<input type='submit' name='btSend'>
		<input type='reset'>
	</fieldset>
	<p><?php echo $message; ?></p>
</body>
</html>