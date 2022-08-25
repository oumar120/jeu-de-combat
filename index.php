<?php 
spl_autoload_register(function($class){
	require $class.'.class.php';
});
session_start();
if(isset($_GET['deconnexion'])){
	session_destroy();
}
if(isset($_SESSION['perso'])){
	$perso=$_SESSION['perso'];
}
$db=new PDO("mysql:host=localhost;dbname=tp","root","");
$manager=new Manager($db);

if(isset($_POST['ajouter']) && isset($_POST['nom']))
{ 
	$perso=new Personne(array('nom'=>$_POST['nom']));
	if(!$perso->nomValide($_POST['nom'])){
		$message="le nom saisi est invalide";
	}elseif($manager->personneExist($perso)){
			$message="ce personnage existe deja";
	}else{
			$manager->addP($perso);
		}
}
elseif(isset($_POST['utiliser']) && isset($_POST['nom'])){
	$perso=new Personne(array(
	   'nom'=>$_POST['nom']
	     ));
    if(empty($perso->getNom())){
    	$message="veuillez saisir un nom";
    }
   	elseif($manager->personneExist($perso))
   	{
   	$perso=$manager->getP($perso);
   }
   else
   {
   	$message="ce personnage n existe pas dans la bdd";
   }
}
elseif(isset($_GET['frapper'])){
	if(!isset($perso)){
		$message="veuillez creer un personnage svp";
	}
	else{
		$personneId=new Personne(array('id'=>$_GET['frapper']));  
		if(!$manager->personneExist($personneId)){
         $message="le personnage que vous voulez frapper n existe pas";
		}
		else{
		 $personne_a_frappe=$manager->getP($personneId);
		 $retour=$perso->frapper($personne_a_frappe);
		 var_dump($retour);
		 switch ($retour) {
		 	case Personne::CEST_MOI:
		 		$message="pourquoi voulez-vous vous frapper? ";
		 		break;
		 	case Personne::PERSONNE_FRAPPE:
		 		$message="vous avez bien ete frappe ";
		 		$manager->updateP($personne_a_frappe);
		 		$manager->updateP($perso);
		 		break;
		 	case Personne::PERSONNE_TUE:
		 		$message="vous avez tué ce personnage ";
		 		$manager->updateP($perso);
		 		$manager->updateP($personne_a_frappe);
		 		break;
		 	
		 }
		}
	}
}

 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>TP mini jeu</title>
</head>
<body>
	<p>nombre de personnes crées:<?php echo $manager->count() ?></p>
<?php 
if(isset($message))
{
	echo $message;
}
if (isset($perso)) {
 ?>
<fieldset>
	<legend>mes informations</legend>
	<p>
		Nom:<?php echo $perso->getNom() ?>
		Degat:<?php echo $perso->getDegat() ?>
	</p>
</fieldset>
<fieldset>
	<legend>Qui frappé?</legend>
	<p>
		<?php 
          $persos=$manager->getList();
          if(empty($persos)){
          	echo "personne à frapper";
          }
          else{
          	foreach($persos as $per){
          		echo '<a href="?frapper=',$per->getId(),'">',htmlspecialchars($per->getNom()),'</a>'.'<br>';
          	}
          }
		 ?>
	</p>

</fieldset>
<?php }
else{ ?>
<form action="index.php" method="post">
	<label>saisir un nom:</label>
	<input type="text" name="nom">
	<input type="submit" name="ajouter" value="ajouter un personnage">
	<input type="submit" name="utiliser" value="utiliser un personnage">
</form>
<?php } ?>
</body>
</html>
<?php 
if(isset($perso))
{
	$_SESSION['perso']=$perso;
}

 ?>