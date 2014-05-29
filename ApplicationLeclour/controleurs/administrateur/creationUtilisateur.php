<?php
require_once _PATH_ . "modele/groupe.class.php";
require_once _PATH_ . "modele/magasin.class.php";

if (!empty ( $post )) {

	$nom = $post ['nom'];
	$prenom = $post ['prenom'];
	$login = $post ['login'];
	$mdp = $post ['mdp'];
	$groupe = intval ( $post ['profil'] );
	$magasin = intval ( $post ['entite'] );
	if($nom=="" || $prenom=="" || $login="" || $mdp==""){
		$parameters ['error'] = "Tous les champs doivent être remplis";
	}else{
		try {
			Utilisateurs::creer ( $nom, $prenom, $login, $mdp, $groupe, $magasin );
			$parameters ['creation'] = "reussi";

		} catch ( Exception $e ) {
			$parameters ['error'] = ($e->getMessage ());
		}
	}
}

$parameters ['groupes'] = Groupe::getGroupes ();
$parameters ['magasins'] = Magasin::getMagasins ();
$smarty->assign ( 'parameters', $parameters );
?>