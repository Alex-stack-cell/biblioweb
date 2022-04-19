<?php
session_start();

require 'config.php';

//Connexion DB
$link = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);

if($link) {
	//Récupérer les pays
	$query = "SELECT DISTINCT nationality FROM `authors` ORDER BY nationality";
				
	$result = mysqli_query($link, $query);
	
	if($result) {
		while(($data = mysqli_fetch_row($result)) !== null) {
			$countries[] = $data[0];
		}//	var_dump($countries);
		
		mysqli_free_result($result);
	} else {
		$message = 'Erreur de requête (pays).';
	}
	
	//Traitement de la commande : Récupérer les auteurs du pays sélectionné
	if(isset($_GET['btSearch'])) {
		if(!empty($_GET['country'])) {
			//Récupérer les données entrantes
			$country = $_GET['country'];
			
			//Nettoyer les données entrantes
			//Empêcher l'injection SQL avec mysqli_real_escape_string
			$sqlCountry = mysqli_real_escape_string($link, $country);

			//Préparer la requête
			$query = "SELECT firstname, lastname FROM authors 
				WHERE nationality='$sqlCountry' ORDER BY 2";

			//Envoyer la requête
			$result = mysqli_query($link, $query);
			
			//Extraire les données
			if($result) {
				$authors = mysqli_fetch_all($result, MYSQLI_ASSOC);
				
				//Sauver la dernière recherche
				$_SESSION['lastSearch'] = $country;
				
				mysqli_free_result($result);
			} else {
				$message = 'Erreur de requête.';
			}
		} else {
			$message = 'Veuillez sélectionner un pays.';
		}
	}
	
	mysqli_close($link);
} else {
	$message = 'Erreur de connexion.';
}

require 'includes/header.php';
?>
	<div class="row">
		<div class="col">
			<h1>Biblioweb</h1>
			<form>
				<select name="country">
					<option>Choisir un pays</option>
				<?php foreach($countries as $pays) : ?>
					<option <?= ($_SESSION['lastSearch'] && $_SESSION['lastSearch']==$pays) ? 'selected':''?>><?= $pays; ?></option>
				<?php endforeach; ?>
				</select>
				<button name="btSearch">Rechercher</button>
			</form>
			
		<?php if(!empty($authors)) : ?>	
			<table>
				<!-- Empêcher l'attaque XSS avec htmlentities/htmlspecialchars -->
				<caption style="caption-side: top;"><?= htmlentities($country); ?></caption>
				<tbody>
				<?php foreach($authors as $author) : ?>
					<tr>
						<td><?= $author['firstname'].' '.$author['lastname'] ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
		</div><!-- col -->
	</div><!-- row -->
<?php
require 'includes/footer.php';
?>