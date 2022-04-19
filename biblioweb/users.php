<?php
session_start();

//Initialisation des variables, constantes et environnement 
require 'includes/header.php';
$message = '';
DEFINE('MAX_UPDATES_PER_SESSION',2);
$users = [];
require 'config.php';
/*$_SESSION['updates'] = [
    'users'=> 
        [
        ],
    'nb' =>
        [
        ],
];
*/
// Sécurisation d'accès : Espace réservé aux membres
require 'secure.php'; 

// Sécurisation d'accès : Espace réservé aux admin
if($_SESSION['statut']!=="admin"){
    header('Location:index.php');
    header('Statut:302');
    exit;
}
// Espace réservé aux Admin => DONE
// Admin peut promouvoir et/ou rétrograder un utilisateur => DONE
// Expert ne peut plus être promu => DONE
// Novice ne peut plus être rétrograder => DONE 
// Habitué peut être promu ou rétrograder => DONE
// Afficher les boutons adéquats => DONE
// L'admin ne peut pas changer l'état d'un même utilisateur plus de 2 fois/session
    // Il faut compter le nombre de fois que l'admin se co et modifie le statut d'un utilisateur
        // Stocker l'information de "comptage" dans une variable
        // Si ce nombre est supérieur à la limite fixée alors il ne peut pas modifié le statut
            // Il faut mémoriser la référence de l'utilisateur et son nb de modification

// Afficher un message pour chaque cas

?>
<?php
@$link = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);
@mysqli_set_charset($link,'UTF-8');
//if() {
    if(isset($_POST['btLvlUp'])||isset($_POST['btLvlDown'])){
        $user_statut = $_POST['statut'];
        $user_id = (int)$_POST['id'];
        
        // Promouvoir
        if(isset($_POST['btLvlUp'])) {  
            switch ($user_statut) {
                case"novice":
                    $user_statut ="habitué";
                    break;
                default:
                    $user_statut = "expert";        
            }
        } else { // Rétrograder
            switch ($user_statut) {
                case"habitué":
                    $user_statut ="novice";
                    break;
                default:
                    $user_statut = "habitué";        
            }
        }
        
        // Sécurisation des données : Injection SQL
        mysqli_real_escape_string($link,$user_statut);
        mysqli_real_escape_string($link,$user_id);

        // Préparation de la requête
        $query = "UPDATE `users` SET statut = '$user_statut' WHERE id = $user_id ";
        $result= mysqli_query($link,$query);
        
        if($result) {
            // $user['statut'] = $user_statut;
            $message = "Confirmation : Modification réalisée avec succès ! ";

            if(empty($_SESSION['usersUpdated'])){
                $_SESSION['usersUpdated'] = []; // si session vide on créer un tableau
                array_push($_SESSION['usersUpdated'],$user_id);
                $_SESSION['nb_updates'] = 1;
            } elseif(!in_array($user_id,$_SESSION['usersUpdated'])) { // si l'utilisateur E pas on l'ajoute
                array_push($_SESSION['usersUpdated'],$user_id);
            } else {
                $_SESSION['nb_updates']++;
            }
        }
    }
//}

if(!mysqli_connect_error($link)){
    $query = "SELECT id, login, statut FROM `users`";
    $result = mysqli_query($link,$query);
    if($result) {
        while($user = mysqli_fetch_assoc($result)) {
            array_push($users,$user);
        }
    mysqli_free_result($result);
    } else {
        $message = 'Echec. Erreur lors de l\'envoie de la requête!';
    }
    mysqli_close($link);
} else {
    $message = 'Echec ! Erreur de connection avec la base de données !\nCode d\'erreur:'. mysqli_connect_errno($link);
}

// echo "<pre>";
// var_dump($users);
// echo "<pre>";
?>

<table border="1">
    <?php foreach($users as $user):?>
    <tbody>
        <?php if($user['statut']!=="admin"):?>
        <tr>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                <td><?= $user['login']?><input type="hidden" name="id" value="<?=$user['id']?>"></td>
                <td><?= $user['statut']?><input type="hidden" name="statut" value="<?=$user['statut']?>"></td>
                <?php if($user['statut']==="habitué"):?>
                <td><button name=" btLvlUp">Promouvoir</button></td>
                <td><button name="btLvlDown">Rétrograder</button></td>
                <?php elseif ($user['statut']==="novice"):?>
                <td><button name="btLvlUp">Promouvoir</button></td>
                <?php else :?>
                <td></td>
                <td><button name="btLvlDown">Rétrograder</button></td>
                <?php endif;?>
            </form>
        </tr>
        <?php endif;?>
    </tbody>
    <?php endforeach;?>
</table>
<?php
require 'includes/footer.php';
echo $message;
if(!empty($_SESSION['usersUpdated'])) {
    echo "<pre>";
    print_r($_SESSION['usersUpdated']);
    echo "</pre>";
};
if(!empty($_SESSION['nb_updates'])) {
    echo "<pre>";
    print_r($_SESSION['nb_updates']);
    echo "</pre>";
};
?>