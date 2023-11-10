<P>
<B>DEBUTTTTTT DU PROCESSUS :</B>
<BR>
<?php echo " ", date ("h:i:s"); ?>
</P>
<?php

require_once "indexation.php";

//Le dossier de d�part
$path= "docs";

//Augmentation du temps
//d'ex�cution de ce script
set_time_limit (500);

explorerDir($path);

function explorerDir($path)
{	
	$folder = opendir($path);
	
	while($entree = readdir($folder))
	{		
		//On ignore les entr�es
		if($entree != "." && $entree != "..")
		{
			// On v�rifie si il s'agit d'un r�pertoire
			if(is_dir($path."/".$entree))
			{
				$sav_path = $path;
				
				// Construction du path jusqu'au nouveau r�pertoire
				$path .= "/".$entree;
								
				// On parcours le nouveau r�pertoire
				// En appellant la fonction avec le nouveau r�pertoire
				explorerDir($path);
				$path = $sav_path;
			}
			else //C'est un fichier
			{	
				$path_source = $path."/".$entree;				
				
				//TODO : Si c'est un .txt en testant l'extension							
				// TODO : appeller cette fonction si .txt				

				$extension = pathinfo($path_source, PATHINFO_EXTENSION);
				if ($extension == "txt") {								
					indexation($path_source,'./stopwords-fr.txt','./lexique-lemma.csv');
					echo $path_source." ✓<br>";
				} elseif ($extension === "html" || $extension === "htm") {
					indexation($path_source,'./stopwords-fr.txt','./lexique-lemma.csv');
					echo $path_source." ✓<br>";
				} elseif ($extension === 'pdf') {
					indexation($path_source,'./stopwords-fr.txt','./lexique-lemma.csv');
					echo $path_source." ✓<br>";
				} elseif ($extension === 'docx') {
					indexation($path_source,'./stopwords-fr.txt','./lexique-lemma.csv');
					echo $path_source." ✓<br>";
				}


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
