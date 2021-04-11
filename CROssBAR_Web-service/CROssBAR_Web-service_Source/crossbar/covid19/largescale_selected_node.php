<?php

//print_r($_POST);

if(!isset($_POST['node']['Node_Type'])) die();
if(!isset($_POST['node']['name'])) die();

$component = $_POST['node']['Node_Type'];
$name = $_POST['node']['Node_Name'];
$id = $_POST['node']['name'];

switch($_POST['node']['Node_Type']){

	case 'protein':
		$component = 'gene/protein';
		$link = 'https://www.uniprot.org/uniprot/'.$id;
	break;

	case 'organism':
		$link = '#';
	break;

	case 'efo disease':
		if(substr($id,0,3) == 'EFO')
			$link = 'http://www.ebi.ac.uk/efo/'.implode('_', explode(':',$id) );
		else if(substr($id,0,8) == 'Orphanet')
			$link = 'http://www.orpha.net/ORDO/'.implode('_', explode(':',$id) );
		else if(substr($id,0,5) == 'MONDO')
			$link = 'http://purl.obolibrary.org/obo/'.implode('_', explode(':',$id) );
	break;
	case 'kegg disease':
		$link = 'https://www.kegg.jp/dbget-bin/www_bget?'.$id;
	break;

	case 'reactome pathway':
		$link = 'https://reactome.org/content/detail/'.$id;
	break;

	case 'kegg pathway':
		$link = 'https://www.genome.jp/dbget-bin/www_bget?pathway+'.$id;
	break;

	case 'small molecule drug':
	case 'biotech drug':
	case 'biotech drug - vaccine':
	case 'biotech drug - plasma':
	case 'biotech drug - vector':
	case 'biotech drug - oligonucleotide':
		$link = 'https://www.drugbank.ca/drugs/'.$id;
	break;

	case 'compound':
		$link = 'https://www.ebi.ac.uk/chembl/compound_report_card/'.$id.'/';
	break;

	case 'hpo':
		$component = 'Phenotypes (HPO)';
		$link = 'https://hpo.jax.org/app/browse/term/'.$id;
	break;
	default:
		$component = $_POST['node']['Node_Type'];
}
	$id = str_replace(':','_',$id);
?>
<div class="p-1 m-0" id="node_<?=$id?>">
	<ul class="list-group list-group-flush row border border-secondary p-1" id="">
		<li class="list-group-item">Component type: <b><?=ucwords($component)?></b></li>
		<li class="list-group-item">Name: <?=$name?></li>
		<?php if($_POST['node']['Node_Type'] != 'organism'){ ?>
		<li class="list-group-item">ID/link: <a href="<?=$link?>" target=_blank><?=$id?></a></li>
		<?php } ?>
		<li class="list-group-item">Node degree: <?=$_POST['degree']?></li>
	</ul>
</div>
