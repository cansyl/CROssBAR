<?php
	include('database.php');
	$db = new database();
	//$table = htmlspecialchars(mysql_real_escape_string($_GET['id']));

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
		
		#print_r($search);
		$result = $db->search_gene_with_tax($search[$col],$taxids);
		#print_r($result); die();
		# if reviewed filter active, apply to results.
		if($_GET['rew'] === 'true'){
			$revieweds = $db->selectByColumn('proteins_reviewed',array('accession'),PDO::FETCH_COLUMN);
			/*
			foreach($result as $i => $acc){
				print_r($acc);
				if(array_search($acc['acc'],$revieweds,true) === false){
					unset($result[$i]);
				}
			}
			*/
			$sizeofres = count($result);
			for($i=0; $i<$sizeofres; $i++){
				if(array_search($result[$i]['acc'],$revieweds,true) === false){
					#echo $result[$i]['acc'] . 'not found in rews';
					unset($result[$i]);
				}				
			}
			
			#print_r($result); die();
		}
	}else
		$result = $db->search($column,$search);

	#$result = $db->search($column,$search);
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

	$output = array_unique($output);

	if($_GET['id'] === 'protein_input'){
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
		#print_r($output);
	}

	echo json_encode($output);

?>