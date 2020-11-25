<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- CSS -->
	<link rel="stylesheet" href="../styles/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- jQuery and JS bundle w/ Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
	
    <title>Document</title>
</head>

<body>

	<section id="connexion">
        <form action="../admin/index.php" method="POST">
        <input type="text" name="user" id="user" placeholder="Nom d'utilisateur"> <br>
        <input type="password" name="psw" id="psw" placeholder="Mot de passe"> <br>
        <input type="submit" name="connexion" id="connexion" value="Connexion">
        </form>
    </section>

	<header class="container-fluid d-flex justify-content-center align-items-center">
        <h1 class="col-m-4 text-center font-weight-bold">NOM DE L'APPLICATION</h1>
    </header>

	<section class="container-fluid pt-5">
		<div class="row">
			<div class="col">
				<div class="card-deck ">

<?php

include '../scripts/file.php';
include '../scripts/enchere.php';

// Chargement des enchères
$cartonJson = load();

$carton = [];
$offset = 0;

// Création des objets
for ($temp = 0; $temp < count($cartonJson); $temp++) {
	
	// Si une la date d'une enchère est passée, on n'en crée pas d'objet
	if ($cartonJson[$temp]["m_deadline"] > time() ) {
		$carton[$temp - $offset] = new Enchere(
										$cartonJson[$temp]["m_id"],
	                                    $cartonJson[$temp]["m_titre"],
                                        $cartonJson[$temp]["m_desc"],
                                        $cartonJson[$temp]["m_clic"],
                                        $cartonJson[$temp]["m_stepprice"],
                                        $cartonJson[$temp]["m_price"],
										$cartonJson[$temp]["m_deadline"],
										$cartonJson[$temp]["m_steptime"],
										$cartonJson[$temp]["m_image"]
		);
	} else {
		$offset++;
	}
}

// Si une enchère est terminée, on sauvegarde encheres.json avec $carton en l'état pour l'effacer
if ($offset > 0) {
	save($carton);
}

if (isset($_POST['enchere'])) {
	
	$seeker = 0;
	$i = -1;
	
	while ($i == -1 && $seeker < count($carton)) {
		if ($carton[$seeker]->getId() == $_POST['enchere']) {
			$i = $seeker;
		}
		$seeker++;
	}
	
	if ($i != -1) {
		$carton[$i]->enchere();
		
		save($carton);
	}
}

for ($temp = 0; $temp < count($carton); $temp++) {
	$carton[$temp]->display();
}

?>
				</div>
			</div>
		</div>
	</section>

</body>

</html>