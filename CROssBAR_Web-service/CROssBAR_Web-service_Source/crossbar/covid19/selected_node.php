<?php
if(!isset($_POST['node']['Node_Type'])) die();
if(!isset($_POST['node']['Matching_Attribute'][0])) die();

//print_r($_POST); die();

$component = $_POST['node']['Node_Type'];
$name = $_POST['node']['Node_Name'];
$id = $_POST['node']['Matching_Attribute'][0];
//$link = 'https://www.uniprot.org/uniprot/'.$id;

//$component = '';
switch($_POST['node']['Node_Type']){
	case 'organism':
		$link = '#';
	break;
	case 'protein':
		$component = 'gene/protein';
		/*
		$name = $_POST['node']['Node_Name'];
		$id = $_POST['node']['Matching_Attribute'][0];
		*/
		$link = 'https://www.uniprot.org/uniprot/'.$id;
	break;
	case 'Disease':
	case 'efo disease':
		//$component = 'disease';
		if(substr($id,0,3) == 'EFO')
			$link = 'http://www.ebi.ac.uk/efo/'.implode('_', explode(':',$id) );
		else if(substr($id,0,8) == 'Orphanet')
			$link = 'http://www.orpha.net/ORDO/'.implode('_', explode(':',$id) );
		else if(substr($id,0,5) == 'MONDO')
			$link = 'http://purl.obolibrary.org/obo/'.implode('_', explode(':',$id) );
	break;
	case 'kegg_Disease':
	case 'kegg disease':
		//$component = 'disease (KEGG)';
		$link = 'https://www.kegg.jp/dbget-bin/www_bget?'.$id;
	break;
	case 'Pathway':
	case 'reactome pathway':
		/*
		$component = 'reactome pathway';
		$name = $_POST['node']['Node_Name'];
		$id = $_POST['node']['Matching_Attribute'][0];
		*/
		$link = 'https://reactome.org/content/detail/'.$id;
	break;
	case 'kegg_Pathway':
	case 'kegg pathway':
		//print_r();
		//$component = 'pathway (KEGG)';
		$link = 'https://www.genome.jp/dbget-bin/www_bget?pathway+'.$id;
	break;
	case 'Drug':
	case 'small molecule drug':
	case 'biotech drug':
		/*
		$component = 'drug';
		$name = $_POST['node']['Node_Name'];
		$id = $_POST['node']['Matching_Attribute'][0];
		*/
		$link = 'https://www.drugbank.ca/drugs/'.$id;
	break;
	case 'Prediction':
	case 'compound':
		/*
		$component = 'compound';
		$name = $_POST['node']['Node_Name'];
		$id = $_POST['node']['Matching_Attribute'][0];
		*/
		$link = 'https://www.ebi.ac.uk/chembl/compound_report_card/'.$id.'/';
	break;
	case 'HPO':
	case 'hpo':
		$component = 'Phenotypes (HPO)';
		$link = 'https://hpo.jax.org/app/browse/term/'.$id;
	break;
	default:
		$component = $_POST['node']['Node_Type'];
}

?>
<div class="p-1 m-0" id="<?=$id?>">
	<ul class="list-group list-group-flush row border border-secondary p-1" id="">
		<li class="list-group-item">Component type: <b><?=$component?></b></li>
		<li class="list-group-item">Name: <?=$name?></li>
		<?php if($_POST['node']['Node_Type'] != 'organism'){ ?>
		<li class="list-group-item">ID/link: <a href="<?=$link?>" target=_blank><?=$id?></a></li>
		<?php } ?>
		<li class="list-group-item">Node degree: <?=$_POST['degree']?></li>
	</ul>
</div>
