<?php
require('config.php');

session_start();

$message = "";

$newCaptcha = substr(uniqid(),-5,5); //var_dump($secretCaptcha);die;

//Sauver l'ancien captcha
$oldCaptcha = $_SESSION['captcha'] ?? '';

//Sauver le nouveau captcha
$_SESSION['captcha'] = $newCaptcha;

function validatePassword(string $pwd) {
	if(strlen($pwd)<5) {
		return false;
	}
	
	if(preg_match("/\d/",$pwd)===0) {
		return false;
	}
	
	return true;
}

if(isset($_POST['btSignin'])) {
	if(!empty($_POST['login']) && !empty($_POST['pwd'])) {	
		$login = $_POST['login'];
		$pwd = $_POST['pwd'];
		
		//Connexion à la DB
		$link = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);
		
		if($link) {
			//Nettoyer les données entrantes
			$login = mysqli_real_escape_string($link, $login);
			
			//Préparer la requête
			$query = "SELECT id, password, statut, created_at FROM users WHERE login='$login'";
			
			//Envoyer la requête
			$result = mysqli_query($link, $query);
			
			if($result) {
				//Extraire les données
				$user = mysqli_fetch_assoc($result);
				
				if(password_verify($pwd,$user['password'])) {
					//connecter
					$_SESSION['login'] = $login;
					$_SESSION['statut'] = $user['statut'];
					$_SESSION['created_at'] = $user['created_at'];
					$_SESSION['id'] = $user['id'];
					//Redirection
					header('Location: index.php');
					header('Status: 302');
					exit;
				}
				
				mysqli_free_result($result);
			}
			
			mysqli_close($link);
		}
	}
} elseif(isset($_POST['btSignup'])) {
	if(!empty($_POST['login']) && !empty($_POST['email']) 
		&& !empty($_POST['pwd'] && !empty($_POST['pwd_conf']) && !empty($_POST['captcha']))) {
		$login = $_POST['login'];
		$email = $_POST['email'];
		$pwd = $_POST['pwd'];
		$pwd_conf = $_POST['pwd_conf'];
		$captcha = $_POST['captcha'];
		// var_dump($captcha, $oldCaptcha, $newCaptcha);
		
		if($captcha == $oldCaptcha) {
			if(filter_var($email,FILTER_VALIDATE_EMAIL)) {
				if($pwd==$pwd_conf) {
					if(validatePassword($pwd)) {
						//Inscription
						
						//Connexion à la DB
						$link = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);
							
						if($link) {
							//Nettoyer les données entrantes
							$login = mysqli_real_escape_string($link,$login);
							$email = mysqli_real_escape_string($link,$email);
							
							$pwd = password_hash($pwd,PASSWORD_BCRYPT);
							
							//Préparer la requête
							$query = "INSERT INTO `users` (`login`, `email`, `statut`, `password`, `created_at`) 
								VALUES ('$login', '$email', 'novice', '$pwd', current_timestamp())";
							
							//Envoyer la requête
							$result = mysqli_query($link, $query);
							
							//Vérifier si elle a réussi
							if($result && mysqli_affected_rows($link)>0) {
								//Connexion (facultative)
								$_SESSION['login'] = $login;
								
								//redirection
								header('Location: index.php');
								header('Status: 302');
								exit;
							}
							//Se déconnecter
							mysqli_close($link);
						}
					} else {
						$message = 'Le mot de passe doit avoir au mois 5 caractères dont 1 chiffre!';
					}
				} else {
					$message = 'Les mots de passe ne correspondent pas!';
				}
			} else {
				$message = 'Votre email ne semble pas valide!';
			}
		} else {
			$message = 'Le code de vérification ne correspond pas à l\'image!';
		}
	} else {
		$message = 'Veuillez remplir tous les champs!';
	}
} elseif(isset($_GET['logout'])) {
	//Déconnexion
	session_unset();
	session_destroy();
	
	//Redirection
	header('Location: index.php');
	header('Status: 302');
	exit;
}

?>
<!doctype html>
<html lang="fr">

<head>
    <title>DB Access</title>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous">
    </script>
    <style>
    .col {
        border: 1px solid silver;
        padding: 15px;
    }

    .col:nth-child(2) {
        background-color: black;
        color: white;
    }

    .help-block {
        font-size: 0.5em;
        font-style: italic;
        color: silver;
    }

    button {
        margin-top: 15px;
    }
    </style>
</head>

<body>
    <main class="container">
        <div class="row">
            <div class="col">
                <form class="form-horizontal" action="signin.php" method="post">
                    <fieldset>

                        <!-- Form Name -->
                        <legend>Connexion</legend>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-8 control-label" for="login">Login</label>
                            <div class="col-md-8">
                                <input id="login" name="login" type="text" value="<?= $_POST['login'] ?? '' ?>"
                                    placeholder="Entrez votre login" class="form-control input-md" required="">
                                <span class="help-block">Veuillez entrer votre login</span>
                            </div>
                        </div>

                        <!-- Password input-->
                        <div class="form-group">
                            <label class="col-md-8 control-label" for="pwd">Password</label>
                            <div class="col-md-8">
                                <input id="pwd" name="pwd" type="password" placeholder="Entrez votre mot de passe"
                                    class="form-control input-md" required="">
                                <span class="help-block">Entrez votre mot de passe</span>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                            <div class="col-md-8">
                                <button id="btSignin" name="btSignin" class="btn btn-primary">Se connecter</button>
                            </div>
                        </div>

                    </fieldset>
                </form>
                <p class="text-center"><a href="password_reset.php">Mot de passe oublié ?</a></p>
            </div><!-- col -->
            <div class="col">
                <form class="form-horizontal" action="signin.php" method="post">
                    <fieldset>

                        <!-- Form Name -->
                        <legend>Inscription</legend>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-8 control-label" for="login">Login</label>
                            <div class="col-md-8">
                                <input id="login" name="login" type="text" value="<?= $_POST['login'] ?? '' ?>"
                                    placeholder="Entrez votre login" class="form-control input-md" required="">
                                <span class="help-block">Veuillez entrer votre login</span>
                            </div>
                        </div>

                        <!-- Password input-->
                        <div class="form-group">
                            <label class="col-md-8 control-label" for="pwd">Password</label>
                            <div class="col-md-8">
                                <input id="pwd" name="pwd" type="password" placeholder="Entrez votre mot de passe"
                                    class="form-control input-md" required="">
                                <span class="help-block">Entrez votre mot de passe</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-8 control-label" for="pwd_conf">Password Confirm</label>
                            <div class="col-md-8">
                                <input id="pwd_conf" name="pwd_conf" type="password"
                                    placeholder="Confirmez votre nouveau mot de passe" class="form-control input-md"
                                    required="">
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-8 control-label" for="email">Email</label>
                            <div class="col-md-8">
                                <input id="email" name="email" type="email" value="<?= $_POST['email'] ?? '' ?>"
                                    placeholder="Entrez votre email" class="form-control input-md" required="">
                            </div>
                        </div>

                        <!-- Text input - Captcha -->
                        <div class="form-group">
                            <label class="col-md-8 control-label" for="captcha">Verification</label>
                            <div class="col-md-8">
                                <!-- <img src="captcha_generator.php?captcha=<?= $newCaptcha; ?>" alt="" width="250"> -->
                                <p><?= $newCaptcha; ?></p>
                                <input id="captcha" name="captcha" type="text"
                                    placeholder="Entrez le texte dans l'image" class="form-control input-md"
                                    required="">
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                            <div class="col-md-8">
                                <button id="btSignup" name="btSignup" class="btn btn-success">S'inscrire</button>
                            </div>
                        </div>

                    </fieldset>
                </form>
                <p><?= $message ?></p>
            </div><!-- col -->
        </div><!-- row -->
    </main>

</html>