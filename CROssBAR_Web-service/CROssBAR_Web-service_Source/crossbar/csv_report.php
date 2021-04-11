<?php

	function cleancommas($str){
		return str_replace(',','-',$str);
	}

	$csv = fopen('data/'.$_POST['params'].'.csv', "w");
	fwrite($csv,"Core Proteins,Interacting Proteins,Pathways,HPO terms,Diseases,Targeting Drugs,Experimental Bioactive Compounds (pChEMBL),Predicted interacting Compounds\n");
	foreach($proteins as $uniprotid => $protein){

		fwrite($csv,$protein['display_name'].' ('.$uniprotid."),");

		if(isset($protein['proteins'])){
			$str = '';
			#foreach($protein['proteins'] as $inter){
			foreach($protein['proteins'] as $inter => $inter_arr){
				$str .= $proteins[$inter]['display_name'].' ('.$inter.'); ';
			}
			fwrite($csv,substr($str,0,-2).",");
		}else
			fwrite($csv,",");

		if(isset($protein['pathways'])){
			#fwrite($csv,(string)implode(', ',$interacts['Pathway']));
			$str = '';
			foreach($protein['pathways'] as $id){
				$str .= $id . ' ' . cleancommas($pathways[$id]['display_name']) . '[scr:' .  round($pathways[$id]['enrichScore']) . ']; ';
				#fwrite($csv,(string)implode(', ',$interacts['Pathway']));
			}
			fwrite($csv,substr($str,0,-2).",");
		}else
			fwrite($csv,",");

		if(isset($protein['hpos'])){
			#fwrite($csv,(string)implode(', ',$interacts['Pathway']));
			$str = '';
			foreach($protein['hpos'] as $id){
				$str .= $id . ' ' . cleancommas($hpos[$id]['display_name']) . '[scr:' .  round($hpos[$id]['enrichScore']) . ']; ';
				#fwrite($csv,(string)implode(', ',$interacts['Pathway']));
			}
			fwrite($csv,substr($str,0,-2).",");
		}else
			fwrite($csv,",");

		if(isset($protein['diseases'])){
			$str = '';
			foreach($protein['diseases'] as $id){
				$str .= $id . ' ' . cleancommas($diseases[$id]['display_name']) . ' (' . $diseases[$id]['source'] . ')' . '[scr:' .  round($diseases[$id]['enrichScore']) . ']; ';
			}
			fwrite($csv,substr($str,0,-2).",");
		}else
			fwrite($csv,",");

		if(isset($protein['drugs'])){
			$str = '';
			foreach($protein['drugs'] as $id){
				$str .= $id . ' ' . cleancommas($drugs[$id]['display_name']) . '[scr:' .  round($drugs[$id]['enrichScore']) . ']; ';
			}
			fwrite($csv,substr($str,0,-2).",");
		}else
			fwrite($csv,",");

		if(isset($protein['compounds'])){
			$str = '';
			foreach($protein['compounds'] as $id){
				#$pchemblValue_between_them = $node_compounds[$drug->chembl_id]['pchembl_valOf_edges'][$e];
				#$str .= $id . ' (pChEMBL: ' . $compounds[$id]['pchembl_value'] . ') [scr:' .  round($compounds[$id]['enrichScore'],2) . ']; ';
				$str .= $id . ' (pChEMBL: ' . $compounds[$id]['pchembl_valOf_edges'][$uniprotid] . ') [scr:' .  round($compounds[$id]['enrichScore'],2) . ']; ';
			}
			fwrite($csv,substr($str,0,-2).",");
		}else
			fwrite($csv,",");

		if(isset($protein['predictions'])){
			$str = '';
			foreach($protein['predictions'] as $id){
				$str .= $id . ' [scr:' .  round($predictions[$id]['enrichScore'],2) . ']; ';
			}
			#fwrite($csv,substr($str,0,-2).",");
			fwrite($csv,substr($str,0,-2));
		}

		fwrite($csv,"\n");
	}
	fclose($csv);
	
	# rapor dosyasi kapatiliyor...
	fclose($report);
	
?>