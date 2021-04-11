<?php

$last_report = fopen('data/'.$_POST['params'].'_beta.txt', "w");

fwrite($last_report, 'CROssBAR Knowledge Graph (KG) Construction Query REPORT'."\n");
fwrite($last_report, '*******************************************************'."\n\n");
fwrite($last_report, 'Query Parameters'."\n");
fwrite($last_report, '----------------'."\n\n");
fwrite($last_report, 'Query Terms:'."\n");

$protein_tmp_str = '';
$pathway_tmp_str = '';
$kpathway_tmp_str = '';
$disease_tmp_str = '';
$kdisease_tmp_str = '';
$hpo_tmp_str = '';
$drug_tmp_str = '';
$compound_tmp_str = '';

	foreach($file['search'] as $query_parameter){

		$qt = key($query_parameter);
		
		switch($qt){
			case 'Protein':
				$query_prots = explode('|', $query_parameter['Protein']);

				foreach($query_prots as $qp){
					foreach($proteins as $acc => $p){
						if($p['display_name'] == $qp){
							$protein_tmp_str .= $qp . ' (database id: ' . $acc . '), ';
							break;
						}
						
					}
				}
				$protein_tmp_str = substr($protein_tmp_str,0,-2);
			break;
			case 'Pathway':
				foreach($query_parameter['Pathway'] as $id => $name)
					$pathway_tmp_str .= $name . ' (database id: '. $id . '), ';
				$pathway_tmp_str = substr($pathway_tmp_str,0,-2);
			break;
			case 'KEGG Pathway':
				foreach($query_parameter['KEGG Pathway'] as $id => $name)
					$kpathway_tmp_str .= $name . ' (database id: '. $id . '), ';
				$kpathway_tmp_str = substr($kpathway_tmp_str,0,-2);
			break;
			case 'Disease':
				foreach($query_parameter['Disease'] as $id => $name)
					$disease_tmp_str .= $name . ' (database id: '. $id . '), ';
				$disease_tmp_str = substr($disease_tmp_str,0,-2);
			break;
			case 'KEGG Disease':
				foreach($query_parameter['KEGG Disease'] as $id => $name)
					$kdisease_tmp_str .= $name . ' (database id: '. $id . '), ';
				$kdisease_tmp_str = substr($kdisease_tmp_str,0,-2);
			break;
			case 'HPO':
				foreach($query_parameter['HPO'] as $hpoo)
					$hpo_tmp_str .= $hpoo . ', ';
				$hpo_tmp_str = substr($hpo_tmp_str,0,-2);
			break;
			case 'Drug':
				foreach($query_parameter['Drug'] as $drg)
					foreach($drg as $id => $name)
						$drug_tmp_str .= $name . ' (database id: '. $id . '), ';
				$drug_tmp_str = substr($drug_tmp_str,0,-2);
			break;
			case 'Compound':
				foreach($query_parameter['Compound'] as $id => $name)
					$compound_tmp_str .= $name . ', ';
				$compound_tmp_str = substr($compound_tmp_str,0,-2);
			break;
		}
	}

	if($protein_tmp_str !== ''){
		fwrite($last_report, "\t".'Gene(s)/Protein(s): '.$protein_tmp_str."\n");
	}
	
	if($pathway_tmp_str !== ''){
		fwrite($last_report, "\t".'Pathway(s) - Reactome: '.$pathway_tmp_str."\n");
	}

	if($kpathway_tmp_str !== ''){
		fwrite($last_report, "\t".'Pathway(s) - KEGG: '.$kpathway_tmp_str."\n");
	}
	
	if($disease_tmp_str !== ''){
		fwrite($last_report, "\t".'Disease(s) - EFO: '.$disease_tmp_str."\n");
	}
	
	if($kdisease_tmp_str !== ''){
		fwrite($last_report, "\t".'Disease(s) - KEGG: '.$kdisease_tmp_str."\n");
	}
	
	if($hpo_tmp_str !== ''){
		fwrite($last_report, "\t".'Phenotype(s): '.$hpo_tmp_str."\n");
	}
	
	if($drug_tmp_str !== ''){
		fwrite($last_report, "\t".'Drug(s): '.$drug_tmp_str."\n");
	}
	
	if($compound_tmp_str !== ''){
		fwrite($last_report, "\t".'Compound(s): '.$compound_tmp_str."\n");
	}

$all_equal = 0;
if($file['options']['num_of_fn_nodes'] == $file['options']['num_of_pathways'])
	if($file['options']['num_of_pathways'] == $file['options']['num_of_phenotypes'])
		if($file['options']['num_of_phenotypes'] == $file['options']['num_of_drugs'])
			if($file['options']['num_of_drugs'] == $file['options']['num_of_diseases'])
				if($file['options']['num_of_diseases'] == $file['options']['num_of_compounds'])
					$all_equal = 1;

fwrite($last_report, 'Number of the most enriched nodes from each component to be incorporated into the KG: ');
if($all_equal)
	fwrite($last_report, $file['options']['num_of_drugs']."\n");
else{
	fwrite($last_report, "\n\t".'Neighbouring genes/proteins: '.$file['options']['num_of_fn_nodes']."\n");
	fwrite($last_report, "\t".'Pathways: '.$file['options']['num_of_pathways']."\n");
	fwrite($last_report, "\t".'Diseases: '.$file['options']['num_of_diseases']."\n");
	fwrite($last_report, "\t".'Phenotypes: '.$file['options']['num_of_phenotypes']."\n");
	fwrite($last_report, "\t".'Drugs: '.$file['options']['num_of_drugs']."\n");
	fwrite($last_report, "\t".'Compounds: '.$file['options']['num_of_compounds']."\n");
}

$tmp = '';
$file['options']['fn'] == 1 ? $tmp = 'yes' : $tmp = 'no';
fwrite($last_report, 'Include interacting proteins: '.$tmp."\n");

$file['options']['reviewed_filter'] == 1 ? $tmp = 'yes' : $tmp = 'no';
fwrite($last_report, 'Only reviewed genes/proteins: '.$tmp."\n");

$file['options']['chembl_compounds'] == 1 ? $tmp = 'yes' : $tmp = 'no';
fwrite($last_report, 'Include ChEMBL compounds: '.$tmp."\n");

$file['options']['predictions'] == 1 ? $tmp = 'yes' : $tmp = 'no';
fwrite($last_report, 'Include predicted compounds: '.$tmp."\n");

fwrite($last_report, 'Included tax Id(s): '.implode(', ',$file['options']['tax_ids'])."\n");
fwrite($last_report, 'This query was processed in '.$file['options']['search_runtime'].' seconds.'."\n");
fwrite($last_report, "\n\n\n");

fwrite($last_report, '*  The most enriched 100 terms are shown for each biological component below, only the top terms has been selected to be included in the KG, which are separated from the rest by: '."\n");
fwrite($last_report, '"-*-*-*-*-" (except for the core genes/proteins).'."\n");
fwrite($last_report, '** Terms with the enrichment score: 9999999 or 1000000 are forced into the KG, either because they are query terms or they have a direct connection with a non-gene/protein query term.'."\n");
fwrite($last_report, "\n\n\n");

fwrite($last_report, 'Collected Core Proteins'."\n");
fwrite($last_report, '-----------------------'."\n");
fwrite($last_report, '(All core genes/proteins shown below are incorporated into the KG)'."\n");

fwrite($last_report, file_get_contents('data/'.$_POST['params'].'_cores'));
fwrite($last_report, "\n\n");
unlink('data/'.$_POST['params'].'_cores');

if($file['options']['fn']){
	fwrite($last_report, 'Collected Neighbouring Proteins (Interactors)'."\n");
	fwrite($last_report, '---------------------------------------------'."\n");
	fwrite($last_report, file_get_contents('data/'.$_POST['params'].'_fns'));
	fwrite($last_report, "\n\n");
	unlink('data/'.$_POST['params'].'_fns');
	unlink('pvals/'.$_POST['params'].'_fn.R');
}

fwrite($last_report, 'Collected Reactome Pathways'."\n");
fwrite($last_report, '---------------------------'."\n");
fwrite($last_report, file_get_contents('data/'.$_POST['params'].'_pathways'));
fwrite($last_report, "\n\n");
unlink('data/'.$_POST['params'].'_pathways');
unlink('pvals/'.$_POST['params'].'_pathways.R');

fwrite($last_report, 'Collected KEGG Pathways'."\n");
fwrite($last_report, '-----------------------'."\n");
fwrite($last_report, file_get_contents('data/'.$_POST['params'].'_kpathways'));
fwrite($last_report, "\n\n");
unlink('data/'.$_POST['params'].'_kpathways');
unlink('pvals/'.$_POST['params'].'_kpathways.R');

fwrite($last_report, 'Collected EFO Diseases'."\n");
fwrite($last_report, '----------------------'."\n");
fwrite($last_report, file_get_contents('data/'.$_POST['params'].'_diseases'));
fwrite($last_report, "\n\n");
unlink('data/'.$_POST['params'].'_diseases');
unlink('pvals/'.$_POST['params'].'_diseases.R');

fwrite($last_report, 'Collected KEGG Diseases'."\n");
fwrite($last_report, '-----------------------'."\n");
fwrite($last_report, file_get_contents('data/'.$_POST['params'].'_kdiseases'));
fwrite($last_report, "\n\n");
unlink('data/'.$_POST['params'].'_kdiseases');
unlink('pvals/'.$_POST['params'].'_kdiseases.R');

fwrite($last_report, 'Collected HPO Terms (Phenotypes)'."\n");
fwrite($last_report, '--------------------------------'."\n");
fwrite($last_report, file_get_contents('data/'.$_POST['params'].'_hpos'));
fwrite($last_report, "\n\n");
unlink('data/'.$_POST['params'].'_hpos');
unlink('pvals/'.$_POST['params'].'_hpos.R');

fwrite($last_report, 'Collected Drugs'."\n");
fwrite($last_report, '---------------'."\n");
fwrite($last_report, file_get_contents('data/'.$_POST['params'].'_drugs'));
fwrite($last_report, "\n\n");
unlink('data/'.$_POST['params'].'_drugs');
unlink('pvals/'.$_POST['params'].'_drugs.R');

if($file['options']['chembl_compounds']){
	fwrite($last_report, 'Collected Bioactive Compounds (from ChEMBL)'."\n");
	fwrite($last_report, '-------------------------------------------'."\n");
	fwrite($last_report, file_get_contents('data/'.$_POST['params'].'_compounds'));
	fwrite($last_report, "\n\n");
	unlink('data/'.$_POST['params'].'_compounds');
	unlink('pvals/'.$_POST['params'].'_compounds.R');
}

if($file['options']['predictions']){
	fwrite($last_report, 'Collected Predicted Compounds (from DEEPScreen)'."\n");
	fwrite($last_report, '-----------------------------------------------'."\n");
	fwrite($last_report, file_get_contents('data/'.$_POST['params'].'_predictions'));
	fwrite($last_report, "\n\n");
	unlink('data/'.$_POST['params'].'_predictions');
	unlink('pvals/'.$_POST['params'].'_predictions.R');
}

fwrite($last_report, "\n".'Other Messages Related to the Query'."\n".'-----------------------------------'."\n\n");
fwrite($last_report,file_get_contents('data/'.$_POST['params'].'.txt'));
fclose($last_report);

unlink('data/'.$_POST['params'].'.txt');
rename('data/'.$_POST['params'].'_beta.txt','data/'.$_POST['params'].'.txt');

?>