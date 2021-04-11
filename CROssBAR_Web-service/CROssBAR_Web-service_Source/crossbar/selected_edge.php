<?php

if(!isset($_POST['node1']['Node_Type']) or !isset($_POST['node2']['Node_Type'])) die();

$component1 = '';
switch($_POST['node1']['Node_Type']){
	case 'Protein':
	case 'Protein_N':
		$component1 = 'gene/protein';
		$link1 = 'https://www.uniprot.org/uniprot/'.$_POST['node1']['id'];
	break;
	case 'Disease':
		$component1 = 'disease (EFO)';
		if(substr($_POST['node1']['id'],0,3) == 'EFO')
			$link1 = 'http://www.ebi.ac.uk/efo/'.implode('_', explode(':',$_POST['node1']['id']) );
		else if(substr($_POST['node1']['id'],0,8) == 'Orphanet')
			$link1 = 'http://www.orpha.net/ORDO/'.implode('_', explode(':',$_POST['node1']['id']) );
		else if(substr($_POST['node1']['id'],0,5) == 'MONDO')
			$link1 = 'http://purl.obolibrary.org/obo/'.implode('_', explode(':',$_POST['node1']['id']) );

	break;
	case 'kegg_Disease':
		$component1 = 'disease (KEGG)';
		$link1 = 'https://www.kegg.jp/dbget-bin/www_bget?'.$_POST['node1']['id'];
	break;
	case 'Pathway':
		$component1 = 'pathway (Reactome)';
		$link1 = 'https://reactome.org/content/detail/'.$_POST['node1']['id'];
	break;
	case 'kegg_Pathway':
		$component1 = 'pathway (KEGG)';
		$link1 = 'https://www.genome.jp/dbget-bin/www_bget?pathway+'.$_POST['node1']['id'];
	break;
	case 'Drug':
		$component1 = 'drug';
		$link1 = 'https://www.drugbank.ca/drugs/'.$_POST['node1']['id'];
	break;
	case 'Prediction':
	case 'Compound':
		$component1 = 'compound';
		$link1 = 'https://www.ebi.ac.uk/chembl/compound_report_card/'.$_POST['node1']['id'].'/';
	break;
	case 'HPO':
		$component1 = 'phenotype';
		$link1 = 'https://hpo.jax.org/app/browse/term/'.$_POST['node1']['id'];
	break;
	default:
		$component1 = $_POST['node1']['Node_Type'];
}

$component2 = '';
switch($_POST['node2']['Node_Type']){
	case 'Protein':
	case 'Protein_N':
		$component2 = 'gene/protein';
		$link2 = 'https://www.uniprot.org/uniprot/'.$_POST['node2']['id'];
	break;
	case 'Disease':
		$component2 = 'disease (EFO)';
		if(substr($_POST['node2']['id'],0,3) == 'EFO')
			$link2 = 'http://www.ebi.ac.uk/efo/'.implode('_', explode(':',$_POST['node2']['id']) );
		else if(substr($_POST['node2']['id'],0,8) == 'Orphanet')
			$link2 = 'http://www.orpha.net/ORDO/'.implode('_', explode(':',$_POST['node2']['id']) );
		else if(substr($_POST['node2']['id'],0,5) == 'MONDO')
			$link2 = 'http://purl.obolibrary.org/obo/'.implode('_', explode(':',$_POST['node2']['id']) );
	break;
	case 'kegg_Disease':
		$component2 = 'disease (KEGG)';
		$link2 = 'https://www.kegg.jp/dbget-bin/www_bget?'.$_POST['node2']['id'];
	break;
	case 'Pathway':
		$component2 = 'pathway (Reactome)';
		$link2 = 'https://reactome.org/content/detail/'.$_POST['node2']['id'];
	break;
	case 'kegg_Pathway':
		$component2 = 'pathway (KEGG)';
		$link2 = 'https://www.genome.jp/dbget-bin/www_bget?pathway+'.$_POST['node2']['id'];
	break;
	case 'Drug':
		$component2 = 'drug';
		$link2 = 'https://www.drugbank.ca/drugs/'.$_POST['node2']['id'];
	break;
	case 'Prediction':
	case 'Compound':
		$component2 = 'compound';
		$link2 = 'https://www.ebi.ac.uk/chembl/compound_report_card/'.$_POST['node2']['id'].'/';
	break;
	case 'HPO':
		$component2 = 'phenotype';
		$link2 = 'https://hpo.jax.org/app/browse/term/'.$_POST['node2']['id'];
	break;
	default:
		$component2 = $_POST['node2']['Node_Type'];
}

$edge_type = '';
$label = '';
switch($_POST['Edge']['Edge_Type']){
	
	case 'Prediction':
		$edge_type = 'Predicted DTI';
		$label = 'targets';
	break;
	case 'predictionChembl':
		$edge_type = 'Predicted DTI';
		$label = 'targets';
	break;
	case 'drugPrediction':
		$edge_type = 'Predicted DTI';
		$label = 'targets';
	break;
	case 'compoundPrediction':
		$edge_type = 'Predicted DTI';
		$label = 'targets';
	break;
	case 'PredChem':
		$edge_type = 'Predicted DTI';
		$label = 'targets';
	break;
	case 'Disease':
		$edge_type = 'Disease-Gene Association';
		$label = 'is related to';
	break;
	case 'kegg_dis_prot':
		$edge_type = 'Disease-Gene Association';
		$label = 'is related to';
	break;
	case 'hpodis':
		$edge_type = 'HPO-Disease Association';
		$label = 'is associated with';
	break;
	case 'HPO':
		$edge_type = 'HPO-Gene Association';
		$label = 'is associated with';
	break;
	case 'kegg_path_prot':
		$edge_type = 'Pathway-Gene/Protein Association';
		$label = 'is involved in';
	break;
	case 'Pathway':
		$edge_type = 'Pathway-Gene/Protein Association';
		$label = 'is involved in';
	break;
	case 'Chembl':
		$edge_type = 'ChEMBL Bioactivity';
		$label = 'targets';
	break;
	case 'drugChembl':
		$edge_type = 'ChEMBL Bioactivity';
		$label = 'targets';
	break;
	case 'Drug':
		$edge_type = 'Drug-Target Interaction (DTI)';
		$label = 'targets';
	break;
	case 'PPI':
		$edge_type = 'Protein-protein Interaction';
		$label = 'interacts with';
	break;
	case 'kegg_dis_drug':
		$edge_type = 'Drug Indication';
		$label = 'indicates';
	break;
	case 'kegg_dis_path':
		$edge_type = 'Disease-Pathway Association';
		$label = 'modulates';
	break;
	default:
		$edge_type = $_POST['Edge']['Edge_Type'];
	
}

$node1 = array();
$node2 = array();
switch($label){
	
	case 'interacts with':
		$node1 = $_POST['node1'];
		$node1['component'] = $component1;
		$node2 = $_POST['node2'];
		$node2['component'] = $component2;
	break;
	case 'targets':
		if( ($_POST['node1']['Node_Type'] == 'Drug') or ($_POST['node1']['Node_Type'] == 'Prediction') or ($_POST['node1']['Node_Type'] == 'Compound') ){
			$node1 = $_POST['node1'];
			$node1['component'] = $component1;
			$node2 = $_POST['node2'];
			$node2['component'] = $component2;
		}else{
			$node1 = $_POST['node2'];
			$node1['component'] = $component2;
			$node2 = $_POST['node1'];
			$node2['component'] = $component1;
			$link_tmp = $link1;
			$link1 = $link2;
			$link2 = $link_tmp;
		}
	break;
	case 'indicates':
		if( ($_POST['node1']['Node_Type'] == 'Drug') ){
			$node1 = $_POST['node1'];
			$node1['component'] = $component1;
			$node2 = $_POST['node2'];
			$node2['component'] = $component2;			
		}else{
			$node1 = $_POST['node2'];
			$node1['component'] = $component2;
			$node2 = $_POST['node1'];
			$node2['component'] = $component1;	
			$link_tmp = $link1;
			$link1 = $link2;
			$link2 = $link_tmp;		
		}
	break;
	case 'modulates':
		if( ($_POST['node1']['Node_Type'] == 'Disease') or ($_POST['node1']['Node_Type'] == 'kegg_Disease') ){
			$node1 = $_POST['node1'];
			$node1['component'] = $component1;
			$node2 = $_POST['node2'];
			$node2['component'] = $component2;			
		}else{
			$node1 = $_POST['node2'];
			$node1['component'] = $component2;
			$node2 = $_POST['node1'];
			$node2['component'] = $component1;
			$link_tmp = $link1;
			$link1 = $link2;
			$link2 = $link_tmp;		
		}
	break;
	case 'is associated with':
		if( ($_POST['node1']['Node_Type'] == 'HPO') ){
			$node1 = $_POST['node1'];
			$node1['component'] = $component1;
			$node2 = $_POST['node2'];
			$node2['component'] = $component2;			
		}else{
			$node1 = $_POST['node2'];
			$node1['component'] = $component2;
			$node2 = $_POST['node1'];
			$node2['component'] = $component1;
			$link_tmp = $link1;
			$link1 = $link2;
			$link2 = $link_tmp;
		}
	break;
	
	case 'is involved in':
		if( ($_POST['node1']['Node_Type'] == 'Protein_N') or ($_POST['node1']['Node_Type'] == 'Protein') ){
			$node1 = $_POST['node1'];
			$node1['component'] = $component1;
			$node2 = $_POST['node2'];
			$node2['component'] = $component2;			
		}else{
			$node1 = $_POST['node2'];
			$node1['component'] = $component2;
			$node2 = $_POST['node1'];
			$node2['component'] = $component1;
			$link_tmp = $link1;
			$link1 = $link2;
			$link2 = $link_tmp;
		}
	break;
	case 'is related to':
		if( ($_POST['node1']['Node_Type'] == 'Protein_N') or ($_POST['node1']['Node_Type'] == 'Protein') ){
			$node1 = $_POST['node2'];
			$node1['component'] = $component2;
			$node2 = $_POST['node1'];
			$node2['component'] = $component1;
			$link_tmp = $link1;
			$link1 = $link2;
			$link2 = $link_tmp;
		}else{
			$node1 = $_POST['node1'];
			$node1['component'] = $component1;
			$node2 = $_POST['node2'];
			$node2['component'] = $component2;
		}	
	break;
}


?>
<div class="p-1 m-0" id="">
	<ul class="list-group list-group-flush row border border-secondary p-1" id="">
		<li class="list-group-item">Edge type: <b> <?=$edge_type?></b></li>
		<li class="list-group-item">Node 1: 
		<a href="<?=$link1?>" target=_blank><?=$node1['display_name']?></a> (<b><?=$node1['component']?></b>)
		</li>
		<li class="list-group-item">"<?=$label?>"</li>
		<li class="list-group-item">Node 2: 
		<a href="<?=$link2?>" target=_blank><?=$node2['display_name']?></a> (<b><?=$node2['component']?></b>)
		</li>
		<?php
		if( isset($_POST['Edge']['pchembl_value']) ){
		?>
		<li class="list-group-item">pChEMBL value: 
		<b><?=$_POST['Edge']['pchembl_value']?></b>
		</li>
		<?php }

		if( isset($_POST['Edge']['assay_chembl_id']) ){
		?>
		<li class="list-group-item">Assay_id: 
			<a href="https://www.ebi.ac.uk/chembl/g/#browse/activities/filter/assay_chembl_id%3A<?=$_POST['Edge']['assay_chembl_id']?>" target=_blank>
			<?=$_POST['Edge']['assay_chembl_id']?>
			</a>
		</li>
		<?php } 

		if( $_POST['Edge']['Edge_Type'] == 'PPI' ){
		?>
		<li class="list-group-item">Confidence score: 
			<b><?=$_POST['Edge']['conf']?></b>
		</li>
		<li class="list-group-item">Interaction_id: 
			<a href="https://www.ebi.ac.uk/intact/interaction/<?=$_POST['Edge']['link']?>" target=_blank>
			<?=$_POST['Edge']['link']?>
			</a>
		</li>
		<?php } ?>
	</ul>
</div>
