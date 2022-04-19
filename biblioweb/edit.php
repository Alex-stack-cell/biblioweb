<?php
session_start();
require 'config.php';
$message='';
$couverture='';
$extension_autorisées = ['jpg','jpeg','gif','png'];
$upload ='';
/*if(!empty(($_POST['refLivre']))) {
	print_r($_POST['refLivre']);
}*/
if(isset($_POST['btEdit'])||isset($_POST['btConfirm'])) {
	
	if(sizeof($_FILES)!=0) {
		switch($_FILES['cover']['error']) {
		case 0:
		$file_name = $_FILES['cover']['name'];
		$file_info = pathinfo($_FILES['cover']['name']);
		$file_extension = $file_info['extension'];
		$file_extension_autorisées = ['jpeg','jpg','gif','png'];
		
		if(in_array($file_extension,$file_extension_autorisées)) {
			$source=$_FILES['cover']['tmp_name'];
			$destination=getcwd()."/IMG/".basename($_FILES['cover']['name']);
			$upload=move_uploaded_file($source,$destination);
			
		} else {
			$file = file_get_contents($_FILES['cover']['name']);
			$upload=imagejpeg(imagecreatefromstring($file),$destination);
		}
		
		if($upload){
			$couverture = imagecreatefromjpeg($destination);
			imagejpeg($couverture,$destination);
		}
		
		$link = @mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		@mysqli_set_charset($link,'utf-8');
		
		if(!mysqli_connect_error()) {
			$ref = mysqli_real_escape_string($link, $_POST['ref']);
			$query="UPDATE `books` SET cover_url = '{$file_name}' WHERE ref={$ref}";
			$result=mysqli_query($link,$query);
			if($result && mysqli_affected_rows($link)>0){
				$message="Votre fichier a bien été enregistré\nVous serez rediriger vers la liste des livres";
				header('Refresh: 3; URL=liste.php');
			}
			mysqli_close($link);
		}else {
			$message="Echec lors de la connexion à la base de donnée.\nCode d'erreur:".mysqli_connect_errno();
		}
		
		break;
		case 1:
		$message='La taille du fichier téléchargé excède la limite maximale autorisée';
		break;
		case 2:
		$message='La taille du fichier téléchargé excède la limite maximale autorisée';
		break;
		case 3:
		$message='Le téléchargement a été interrompu';
		break;
		case 4:
		$message='Aucun fichier téléchargé';
		break;
		}
	}
}

else {
	header("Location:liste.php");
	header("Status:302");
	exit;
}

?>
<!DOCTYPE html>
<html lang='fr'>

<head>
    <meta charset='uft-8'>
    <title>Modification</title>
    <style>
    input {
        display: flex;
        flex-direction: row;
    }
    </style>
</head>

<body>
    <form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="40000">
        <label for="photo">Sélectionner un fichier:</label>
        <input type="file" id="photo" name="cover">
        <input type="hidden" name="ref" value="<?php echo $_POST['refLivre']?>">
        <button name="btConfirm">Ok</button>
        <p><?=$message?></p>
</body>

</html>