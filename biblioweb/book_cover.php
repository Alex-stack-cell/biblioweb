<?php 
require 'includes/header.php';
require 'config.php';
$message ="";
$books = null;
$succes = false;
$file_name = null;
define("MAX_FILE_SIZE_LIMIT",400000);
@$link = mysqli_connect(HOSTNAME,USERNAME, PASSWORD, DATABASE);

if(isset($_POST['btSend'])){ // Validation du boutton d'envoie
    $ref = $_POST['ref'];
    $file = ($_FILES['cover']);
    $file_name = htmlentities($_FILES['cover']['name']);
    $file_type = $_FILES['cover']['type'];
    
    // Validation fichier niv 0 : Fichier vide ou non ?
    if(!empty($file)){ 
        
        // Validation fichier niv 1 : Erreur lors du téléversement ou non ?
        if($file['error']===0) {
            
            //Validation fichier niv 2 : Taille du fichier dépassé ou non ?
            if((int)$file['size']<=MAX_FILE_SIZE_LIMIT){ 

                // Validation fichier niv 3 : Format de fichier ok ou non ?
                $validate_extension = ['image/jpeg','image/png','image/gif','image/bmp'];
                
                if(in_array($file_type,$validate_extension)){
                    $source = $file['tmp_name'];
                    $destination = getcwd()."/IMG/".$file_name;
                    
                    if(move_uploaded_file($source,$destination)){
                        
                        // Mise à jour de la BDD
                        if($link) {
                            $file_name = mysqli_real_escape_string($link,$file_name);
                            $ref  =  mysqli_real_escape_string($link,$ref);
                            $query = 
                            "UPDATE `books`
                            SET cover_url= '$file_name'
                            WHERE ref= $ref
                            ";
                            //var_dump($query);
                            $result = mysqli_query($link,$query);
                            
                            // Si la BDD est màj
                            if($result){
                                $message ="Succès votre couverture a bien été modifié !";
                                $succes = true;
                            } else {
                                $message ="Echec, erreur lors de la mise à jour de la photo de couverture";
                            }
                            
                        }
                        
                    } else {
                        $message = "Echec, une erreur s'est produite." ;
                    }
                    
                } else {
                    $message = "Echec, format de fichier invalide" ;
                }

            } else {
                $message = "Echec, fichier trop lourd !";
            }
            
        } else {
            $message = "Echec, erreur lors du téléversement !";
        }
        
    } else {
        $message = "Echec, veuillez sélectionner un fichier";
    }
    
} else {
    $message = "Veuillez sélectionner un fichier, svp";
}

if(!mysqli_connect_error()){
    mysqli_set_charset($link,"UTF-8");
    $query="SELECT ref, title, lastname, firstname
    FROM `books`
    JOIN authors 
    ON books.author_id = authors.id 
    WHERE cover_url IS NULL
    ORDER BY RAND() LIMIT 1";
    $result= mysqli_query($link,$query);
    
    if($result){
        while($book = mysqli_fetch_assoc($result)){
            $books [] = $book;
        }
        if(!empty($books)){
            $books = $books[0];
        }
        mysqli_free_result($result);
    } else { 
        $message = "Echec lors de l'envoie de requête";
    }
    mysqli_close($link);
} else {
    $message = "Echec de connection à la base de données. Code d'erreur:". mysqli_connect_errno();
}
?>
<?php if(!empty($books)) {?>
<h2><?=$books['title']?></h2>
<p>de <?= $books['firstname'], " ",$books['lastname']?></p>
<?php if($succes) {?>
<img src="<?php echo "IMG/".$file_name?>" alt="<?php echo $file_name?>">
<a class="btn btn-primary" href='<?php echo $_SERVER['PHP_SELF']?>'>Afficher le prochain livre</a>
<?php } else {?>
<form action=" <?php $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="400000">
    <input type="file" name="cover">
    <input type="hidden" name="ref" value="<?php echo $books['ref']?>">
    <button name="btSend">Charger une couverture</button>
</form>
<?php }?>
<p><?=$message?></p>
<?php } else {?>
<p>Plus aucun livre à charger</p>
<?php }
require 'includes/footer.php';
?>