<?php 
session_start();
require 'includes/header.php';
require 'config.php';
require 'secure.php';
$message = "";
$loans = [];
if($_SESSION['statut']!=="admin"){
    // header('Location:index.php',null,302);
    header('Refresh:3,url=index.php');
    echo $message = "Vous devez etre administrateur";
    exit;
}

$link = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);

if($link){
    if(isset($_POST['btDelete'])){
        if(!empty($_POST['id'])) {
            $id = $_POST['id'];
             //echo $id;
            if($link){
                $id = mysqli_real_escape_string($link,$id);
                $query = 
                "DELETE FROM `loans`
                 WHERE id = $id
                 ";
                // var_dump($query);
                $result = mysqli_query($link,$query);
                
                if($result && mysqli_affected_rows($link)>0){
                    $message = "Confirmation : Emprunt supprimé";
                }
            }
        }
    }
    $query = 
    "SELECT loans.id, login, book_id ,user_id, title, return_date 
    FROM `loans` 
    JOIN books ON book_id=books.ref 
    JOIN users ON user_id=users.id 
    ORDER BY login";
    $result = mysqli_query($link,$query);

    if($result){
        while($loan = mysqli_fetch_assoc($result)) {
            $loans [] = $loan;
        }
        mysqli_free_result($result);
    }
     mysqli_close($link);
} else {
    $message = "Echec de connection à la base de données";
}

?>
<table border="2">
    <thead>
        <th>ID</th>
        <th>Login</th>
        <th>Titre</th>
        <th>Date de retour</th>
    </thead>
    <tbody>
        <?php
        if(!empty($loans)):
        ?>
        <?php foreach($loans as $loan) :?>
        <tr>
            <td>
                <?php echo $loan['id']?>
            </td>
            <td>
                <?php echo $loan['login']?>
            </td>
            <td>
                <?php echo $loan['title']?>
            </td>
            <td>
                <?php echo $loan['return_date']?>
            </td>
            <td>
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
                    <input type="hidden" name="id" value="<?=$loan['id']?>">
                    <button name="btDelete">&#10007</button>
                </form>
            </td>
        <tr>
            <?php endforeach ?>
            <?php endif ?>
    </tbody>
</table>
<?php require'includes/footer.php'?>