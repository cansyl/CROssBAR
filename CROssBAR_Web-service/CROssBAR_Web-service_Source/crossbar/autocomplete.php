<?php
	include('database.php');
	$db = new database();

	$table = '';
	$col = '';
	switch($_GET['id']){

		case 'disease_input':
			$table = 'diseases';
			$col = 'disease';
		break;
		case 'drug_input':
			$table = 'drugs';
			$col = 'drug';
		break;
		case 'pathway_input':
			$table = 'pathways';
			$col = 'pathwayName';
		break;
		case 'hpo_input':
			$table = 'hpo_term_names';
			$col = 'term_name';
		break;
		case 'protein_input':
			$table = 'gene_to_acc_tax';
			$col = 'gene';
		break;
	}

	$column[$table] = $col;

	$toSearch = explode('|',$_GET['term']);
	$search[$col] = trim($toSearch[count($toSearch)-1]);

	if($_GET['id'] === 'protein_input'){
		if(isset($_GET['tax'])) $taxids = $_GET['tax'];
		else 					$taxids = array(9606);

		$result = $db->search_gene_with_tax($search[$col],$taxids);
		# if reviewed filter active, apply to results.
		if($_GET['rew'] === 'true'){
			$revieweds = $db->selectByColumn('proteins_reviewed',array('accession'),PDO::FETCH_COLUMN);
			$sizeofres = count($result);
			for($i=0; $i<$sizeofres; $i++){
				if(array_search($result[$i]['acc'],$revieweds,true) === false){
					unset($result[$i]);
				}				
			}

		}
	}else
		$result = $db->search($column,$search);

	$output = array();
	foreach($result as $r)
		$output[] = $r[$col];

	if($_GET['id'] == 'disease_input'){
		$column2['kegg_diseaseterms_enrichment'] = 'kegg_diseasename';
		$search2['kegg_diseasename'] = $search[$col];
		$result2 = $db->search($column2,$search2);
		foreach($result2 as $r)
			$output[] = $r['kegg_diseasename'];
		$output = array_unique($output);
		sort($output);
	}
	if($_GET['id'] == 'pathway_input'){
		$column2['kegg_pathways_enrichment'] = 'kegg_pathwayname';
		$search2['kegg_pathwayname'] = $search[$col];
		$result2 = $db->search($column2,$search2);
		foreach($result2 as $r)
			$output[] = $r['kegg_pathwayname'];
		sort($output);
	}

	if($_GET['id'] === 'drug_input'){
		if(! count($output)){
			$coln = 'drug_id';
			$column2[$table] = $coln;
			$search2[$coln] = $search[$col];
			$result = $db->search($column2,$search2);
			$output = array();
			foreach($result as $r)
				$output[] = $r[$coln];
		}
	}

	$output = array_unique($output);

	if($_GET['id'] === 'protein_input'){
		# since too many entry in protein collection,
		# we are showing just +2 or +3 characters
		sort($output);
		$lengthofgene = strlen($search[$col]);
		if($lengthofgene < 3)
			$lengthofgene += 2;
		else
			$lengthofgene += 3;
		if(count($output) > 20)
			foreach($output as $i => $out)
				if( strlen($out) > $lengthofgene )
					unset($output[$i]);
	}

	echo json_encode($output);

?>