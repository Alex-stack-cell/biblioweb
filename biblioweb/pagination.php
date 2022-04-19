<?php
require 'config.php';
$message = '';
$books = [];
$page = null;
/**
 * Modifiez le script liste.php de la façon suivante :
 * ajoutez un système de pagination
 * affichez 3 livres par page,
 * affichez un menu de navigation permettant de passer à la
 * page suivante,
 * page précédente,
 * première page,
 * dernière page,
 * page indiquée par son numéro.
 */

 // 1 d'abord sélectionner les données dans la bdd => DONE
 // 2 afficher les données sous forme de tableau et restreindre la taille du tableau en trois lignes => DONE
 // 3 afficher les autres données dans les pages suivantes => se baser sur phpmyadmin
 // 4 indiquer un numero pour chaque page

// Connection à la base de données
@$link = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);
$sort = false;
if(!mysqli_connect_error() && !$sort) {
    mysqli_set_charset($link,'UTF-8');
    $query = "SELECT * FROM books"; // Sélectionner toutes les données dans la table livre
    $result = mysqli_query($link,$query);
    
    if($result) {
        while($book = mysqli_fetch_assoc($result)) {
            $books [] = $book;
        }
        mysqli_free_result($result);
    }
    
} else {
    $message = "Echec de connection à la base de données. Code d'erreur : ".mysqli_connect_errno();
}

if(isset($_GET['btnPage'])) {
    if(!empty($_GET['pages'])/*||$_GET['pages']==0*/) { // ligne à modifier si on définit une pagination commencant à 1
        $page = (int) htmlentities($_GET['pages']);
    }
}

// Classer par order croissant/décroissant
if(isset($_POST['btnOrder']) && !$sort) {
    $sort = true;
    if(!empty($_POST['orderBy'])) {
        $order = htmlentities($_POST['orderBy']);
        if($link) {
            $order = mysqli_real_escape_string($link,$order);
            // echo $order;
            $query = "SELECT * FROM books ORDER BY ref $order";
            // var_dump($query);
            $result = mysqli_query($link,$query);
            var_dump($result);
            if($result) {
                while($book = mysqli_fetch_assoc($result)) {
                    $books [] = $book;  
                }
                mysqli_free_result($result);
            }
            
            
            mysqli_close($link);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagination</title>
    <style>
    img {
        width: 80px;
        height: 120px;
    }

    .container {
        text-align: center;
        display: flex;
        flex-direction: row;
        justify-content: center;
    }

    table,
    th,
    td {
        border: 1px solid #000;
    }

    form {
        margin: 1.2rem;
    }
    </style>
</head>

<body>
    <!--Start if : Affichage-->
    <?php if(!empty($books)) { 
        $section = array_chunk($books,3,true); //contient un tableau de livres sectionner en 3 troncons?>
    <div class="container">
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']?>">
            <select name="pages">
                <?php for($i=1; $i<sizeof($section)+1;$i++):?>
                <option value="<?php echo $i?>"><?php echo $i?></option>
                <?php endfor;?>
            </select>
            <button name="btnPage">Ok</button>
        </form>
    </div>
    <div class="container">
        <table>
            <thead>
                <th>ref
                    <form method="post" action=pagination.php?pages=<?=$page?>&btnPage=>
                        <input type="hidden" name="orderBy" value="DESC">
                        <button name="btnOrder">&#8593;</button>
                    </form>
                </th>
                <th>title</th>
                <th>author_id</th>
                <th>description</th>
                <th>cover_url</th>
            </thead>
            <tbody>
                <?php 
                if($page == null) {
                    $page = 1 ; // Par défault la page est mis à 1
                }
                ?>
                <!--Start If : Pagination -->
                <?php if(!$sort) :?>
                <!--Start if : Pas trier-->
                <?php foreach($section[$page-1] as $book):?>
                <!-- Le tableau section contient les données des livres partitionnées en 3 paquets :
                    Paquet 0 : livre de ref => 0, 1 et 2;
                    Paquet 1 : livre de ref => 3, 4 et 5;
                    ...
                    Paquet 8 : livre de ref => 32, 33 et 34;
                    L'indice page (ligne 44) correspond à la page sélectionner
                    Pour rappel chaque page contient 3 livres, ce chiffre correspond donc à la taille de partitionnement
                -->
                <tr>
                    <td><?php echo $book['ref']?></td>
                    <td><?php echo $book['title']?></td>
                    <td><?php echo $book['author_id']?></td>
                    <td><?php echo substr($book['description'],0,50)."..."?></td>
                    <td><img src="biblioweb-covers/<?php echo $book['cover_url']?>"
                            alt="<?php echo $book['cover_url']?>">
                    </td>
                </tr>

                <?php endforeach;?>
                <?php else :?>
                <?php foreach($books as $book):?>
                <tr>
                    <td><?php echo $book['ref']?></td>
                    <td><?php echo $book['title']?></td>
                    <td><?php echo $book['author_id']?></td>
                    <td><?php echo substr($book['description'],0,50)."..."?></td>
                    <td><img src="biblioweb-covers/<?php echo $book['cover_url']?>"
                            alt="<?php echo $book['cover_url']?>">
                    </td>
                </tr>
                <?php endforeach;?>
                <?php endif ;?>
                <!---End if : Tri-->
                <!--End if : Pagination-->
            </tbody>
        </table>
    </div>
    <div class="container">
        <p>Page :
            <?php if($page >1 && $page <9) {?>
            <!--//Si No page > 1 on affiche le symbole : ">"-->
            <a href=pagination.php?pages=<?php echo (int)$page-1?>&btnPage=>&lt</a>
            <?=$page?>
            <a href=pagination.php?pages=<?php echo (int)$page+1?>&btnPage=>&gt</a>
            <?php }elseif($page == sizeof($section)) { ?>
            <a href=pagination.php?pages=<?php echo (int)$page-1?>&btnPage=>&lt</a>
            <?=$page?>
            <?php } else {?>
            <?=$page?>
            <a href=pagination.php?pages=<?php echo (int)$page+1?>&btnPage=>&gt</a>
            <?php }?>
        </p>
    </div>
    <!--End if-->
    <?php }?>
</body>

</html>