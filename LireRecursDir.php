
<P>
<B>DEBUTTTTTT DU PROCESSUS :</B>
<BR>
<?php echo " ", date ("h:i:s"); ?>
</P>
<?php

//Le dossier de départ
$path= "docs";

//Augmentation du temps
//d'exécution de ce script
set_time_limit (500);

explorerDir($path);

function explorerDir($path)
{	
	$folder = opendir($path);
	
	while($entree = readdir($folder))
	{		
		//On ignore les entrées
		if($entree != "." && $entree != "..")
		{
			// On vérifie si il s'agit d'un répertoire
			if(is_dir($path."/".$entree))
			{
				$sav_path = $path;
				
				// Construction du path jusqu'au nouveau répertoire
				$path .= "/".$entree;
								
				// On parcours le nouveau répertoire
				// En appellant la fonction avec le nouveau répertoire
				explorerDir($path);
				$path = $sav_path;
			}
			else //C'est un fichier
			{	
				$path_source = $path."/".$entree;				
				
				//Si c'est un .txt en testant l'extension
				//Alors j'appelle le module de l'indexation
				//...
			}
		}
	}
	closedir($folder);
}
?>

<P>
<B>FINNNNNN DU PROCESSUS :</B>
<BR>
<?php echo " ", date ("h:i:s"); ?>
</P>
