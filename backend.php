<?php

require('Mailin.php');

/* Remplacez ici l'ID de liste par l'ID de la vôtre */	
$list_id = 6;
/* Remplacez ici l'ID du template liste par l'ID du vôtre */	
$template_id = 2;
/* Renseignez ici votre CLÉ API */	
$API_key = "VOTRE CLE";

$mailin = new Mailin("https://api.sendinblue.com/v2.0", $API_key, 5000);

if( isset($_POST['parrain']) ){
	
	/* On recupère les données du parrain... */
	$parrain_email = $_POST['parrain'];	
	/* ...et on vérifie s'il existe dans la liste Sendinblue */
	$parrain_data = $mailin->get_user( array("email"=>$parrain_email) );

	if( isset($_POST['invite']) ){

		/* On recupère les données de l'invité... */
		$invite_email = $_POST['invite'];
		/* ...et on vérifie s'il existe dans la liste Sendinblue */
		$invite_data = $mailin->get_user( array("email"=>$invite_email) );
	
		/* 
		Si l'adresse email de l'invité existe dans la liste Sendinblue,
		cela veut dire qu'elle a déjà été invitée, 
		on stoppe donc le processus. 
		*/
		if( $invite_data["code"] == "success" ){ echo "Cette personne a d&eacute;j&agrave; &eacute;t&eacute; invit&eacute;e."; die(); }
				
		/*
		Si on a bien récupéré les données du parrain dans la liste Sendinblue...
		*/
		if( $parrain_data["code"] == "success" ){
			
			/* ...On ajoute l'invité à la liste en spécifiant l'email de son parrain */
			$invite_create = $mailin->create_update_user( 
				array( 
					"email" => $invite_email, 
					"attributes" => array("REF"=>$parrain_email, 				
						"listid" => array($list_id) 
					) 
				)
			);
			
			/* On récupère le nombre de parrainages que le parrain a déjà effectués... */
			$parrain_data_PAR = $parrain_data["data"]["attributes"]["PAR"];
			
			/*... et on l'incrémente de 1 */
		    $parrain_update = $mailin->create_update_user( 
				array( 
					"email" => $parrain_email, 
					"attributes" => array("PAR"=>$parrain_data_PAR+1) 
				) 
			);
			
		/*
		Si n'a pas réussi à récupérer les données du parrain dans la liste Sendinblue...
		*/		
		} else {
			echo "Cette adresse de parrainage n'existe pas."; die();
		}
	}
	else { 
		echo "Aucun email à inviter n'a &eacute;t&eacute; sp&eacute;cifi&eacute;."; die();
	}
}
else {
	echo "Aucun email de parrain n'a &eacute;t&eacute; sp&eacute;cifi&eacute;."; die();
}

/* ...Si l'ajout de l'email de l'invité à la liste a échoué... */	
if( !$invite_create["code"] == "success" ){ 
	echo "L'enregistrement de l'adresse email &agrave; inviter a &eacute;chou&eacute;."; die();
}

/* ...Si la mise à jour des parrainages du parrain a échoué... */	
if( !$invite_create["code"] == "success" ){ 
	echo "La mise &agrave; jour du nombre de parrainages a &eacute;chou&eacute;."; die();
}

/* On prépare l'email qui va être envoyé à l'invité */	
$data = array( "id" => $template_id,
  "to" => $invite_email,
  "headers" => array("Content-Type"=> "text/html;charset=iso-8859-1", "X-param1"=> "value1", "X-param2"=> "value2", "X-Mailin-custom"=>"my custom value","X-Mailin-tag"=>"my tag value")
);

/* On envoit l'email */
$mailin->send_transactional_template($data);

echo $invite_email." a &eacute;t&eacute; invit&eacute; avec succ&egrave;s !;" 
?>