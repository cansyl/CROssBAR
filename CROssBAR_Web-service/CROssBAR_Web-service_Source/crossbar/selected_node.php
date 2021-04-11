<?php

if(!isset($_POST['node']['Node_Type'])) die();

$component = '';
switch($_POST['node']['Node_Type']){
	case 'Protein':
	case 'Protein_N':
		$component = 'gene/protein';
		$link = 'https://www.uniprot.org/uniprot/'.$_POST['node']['id'];
	break;
	case 'Disease':
		$component = 'disease (EFO)';
		if(substr($_POST['node']['id'],0,3) == 'EFO')
			$link = 'http://www.ebi.ac.uk/efo/'.implode('_', explode(':',$_POST['node']['id']) );
		else if(substr($_POST['node']['id'],0,8) == 'Orphanet')
			$link = 'http://www.orpha.net/ORDO/'.implode('_', explode(':',$_POST['node']['id']) );
		else if(substr($_POST['node']['id'],0,5) == 'MONDO')
			$link = 'http://purl.obolibrary.org/obo/'.implode('_', explode(':',$_POST['node']['id']) );
	break;
	case 'kegg_Disease':
		$component = 'disease (KEGG)';
		$link = 'https://www.kegg.jp/dbget-bin/www_bget?'.$_POST['node']['id'];
	break;
	case 'Pathway':
		$component = 'pathway (Reactome)';
		$link = 'https://reactome.org/content/detail/'.$_POST['node']['id'];
	break;
	case 'kegg_Pathway':
		$component = 'pathway (KEGG)';
		$link = 'https://www.genome.jp/dbget-bin/www_bget?pathway+'.$_POST['node']['id'];
	break;
	case 'Drug':
		$component = 'drug';
		$link = 'https://www.drugbank.ca/drugs/'.$_POST['node']['id'];
	break;
	case 'Prediction':
	case 'Compound':
		$component = 'compound';
		$link = 'https://www.ebi.ac.uk/chembl/compound_report_card/'.$_POST['node']['id'].'/';
	break;
	case 'HPO':
		$component = 'Phenotypes (HPO)';
		$link = 'https://hpo.jax.org/app/browse/term/'.$_POST['node']['id'];
	break;
	default:
		$component = $_POST['node']['Node_Type'];
}

?>
<div class="p-1 m-0" id="<?=$_POST['node']['id']?>">
	<ul class="list-group list-group-flush row border border-secondary p-1" id="">
		<li class="list-group-item">Component type: <b><?=ucwords($component)?></b></li>
		<li class="list-group-item">Name: <?=$_POST['node']['display_name']?></li>
		<li class="list-group-item">ID/link: <a href="<?=$link?>" target=_blank><?=$_POST['node']['id']?></a></li>
		<li class="list-group-item">Node degree: <?=$_POST['degree']?></li>
	</ul>
</div>
