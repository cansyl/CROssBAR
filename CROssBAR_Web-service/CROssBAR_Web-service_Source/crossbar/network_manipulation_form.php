<div id="selected_edges" class="d-none mb-0"></div>
<div id="selected_nodes" class="d-none mb-0"></div>
<div class="row mb-4 d-none" id="makeNewSearchWithSelecteds_btn">
	<button class="btn btn-sm btn-info btn-block" id="makeNewSearchWithSelecteds">New search with selected nodes</button>
</div>

<h5 class="row alert alert-secondary p-1 mb-0 border-bottom-0 border-secondary">
<b>Node Search</b>
</h5>
<div class="row border pl-4 pr-4 pt-0 pb-2 mt-0 mb-2" style="padding-top: 15px !important; padding-bottom: -5px !important;">
	<div class="form-group" style="width:100% !important">
	<input class="form-control form-control-sm" name="node_search" id="node_search" placeholder="Type the name of the node"/>
	<?php if (isset($file_name)){ ?>
	<input type="hidden" name="node_search" id="network_f_name" value="<?=$file_name?>"/>
	<?php } ?>
	</div>
</div>

<h5 class="mt-4 row alert alert-secondary p-1 pb-2 mb-0 border-bottom-0 border-secondary"><b>Graph Options</b></h5>
<div class="form-group row">
	<label class="col-form-label" for="layout">Layout selection:</label>
	<select class="form-control form-control-sm" name="layout" id="layout">
		<option value="CrossBarLayout">CrossBar Layout</option>
		<option value="circle">Circle</option>
		<option value="grid">Grid</option>
		<option value="cose">Cose</option>
		<option value="concentric">Concentric</option>
	</select>
</div>

<div class="form-group row d-none">
	<label for="formControlRange">Size of Nodes</label>
	<input type="range" class="form-control-range" id="sizeOfNodes" min="10" max="200" value="100">
</div>

<div id="CrossBarLayout_settings">
	<h5 class="row alert p-1 mb-0 border-bottom-0 border-secondary">Order of layers (drag & drop):</h5>
	<ul class="list-group d-none row border border-secondary" id="orderingComponents">
		<li class="list-group-item list-group-item-action d-flex p-1" id="Proteins">
			<img src="images/gene_core.png" width="25px" class="mr-2" alt="core protein"/>
			<img src="images/gene_neigh.png" width="25px" class="mr-2" alt="neighbours protein"/>
			<small>Proteins</small>
		</li>
		<li class="list-group-item list-group-item-action p-1" id="Pathways">
			<img src="images/pathways.png" width="25px" class="mr-1" alt="pathways"/>
			<img src="images/pathways_kegg.png" width="25px" class="mr-1" alt="kegg pathways"/>
			<small>Pathways</small>
		</li>
		<li class="list-group-item list-group-item-action p-1" id="Diseases">
			<img src="images/diseases.png" width="25px" class="mr-1" alt="diseases"/>
			<img src="images/phenotypes.png" width="25px" class="mr-1" alt="phenotypes"/>
			<small>Diseases and HPO Terms</small>
		</li>
		<li class="list-group-item list-group-item-action p-1" id="Drugs">
			<img src="images/drugs.png" width="25px" class="mr-1" alt="drugs"/>
			<img src="images/compounds.png" width="25px" class="mr-1" alt="compounds"/>
			<small>Drugs and Compounds</small>
		</li>
	</ul>
	<ul class="list-group row border border-secondary" id="orderingComponents_more">
		<li class="list-group-item list-group-item-action p-1" id="proteins">
			<img src="images/gene_core.png" width="25x" class="mr-1" alt="core protein"/>
			<small>Core genes/proteins</small>
		</li>
		<li class="list-group-item list-group-item-action p-1" id="neighbours">
			<img src="images/gene_neigh.png" width="25px" class="mr-1" alt="neighbours protein"/>
			<small>Neighbouring genes/proteins</small>
		</li>
		<li class="list-group-item list-group-item-action p-1" id="pathways">
			<img src="images/pathways.png" width="25px" class="mr-1" alt="pathways"/>
			<small>Pathways (Reactome & KEGG)</small>
		</li>
		<li class="list-group-item list-group-item-action p-1" id="diseases">
			<img src="images/diseases.png" width="25px" class="mr-1" alt="diseases"/>
			<small>Diseases (OMIM/Orphanet & KEGG)</small>
		</li>
		<li class="list-group-item list-group-item-action p-1" id="hpo">
			<img src="images/phenotypes.png" width="25px" class="mr-1" alt="phenotypes"/>
			<small>Phenotypes (HPO)</small>
		</li>
		<li class="list-group-item list-group-item-action p-1" id="drugs">
			<img src="images/drugs.png" width="25px" class="mr-1" alt="drugs"/>
			<small>Drugs</small>
		</li>
		<li class="list-group-item list-group-item-action p-1" id="compounds">
			<img src="images/compounds.png" width="25px" class="mr-1" alt="compounds"/>
			<small>Compounds</small>
		</li>
	</ul>

	<div class="row mt-0">
		<div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
		  <label class="btn btn-sm btn-outline-secondary col-6">
			<input type="radio" name="layersize" value="less" autocomplete="off"> 4 layers display
		  </label>
		  <label class="btn btn-sm btn-outline-secondary active col-6">
			<input type="radio" name="layersize" value="more" autocomplete="off" checked> 7 layers display
		  </label>
		</div>
	</div>

	<div class="row mt-1">
		<div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
		  <label class="btn btn-sm btn-outline-secondary active col-6">
			<input type="radio" name="clustered" value="merged" autocomplete="off" checked> Nested layers
		  </label>
		  <label class="btn btn-sm btn-outline-secondary col-6">
			<input type="radio" name="clustered" value="separated" autocomplete="off"> Isolated layers
		  </label>
		</div>
	</div>

	<div class="row">
		<button class="btn btn-sm btn-info btn-block" id="changeOrderOfCrossbar">Apply</button>
	</div>
</div>

<div class="mt-2">
	<div class="row">
		<button class="btn btn-sm btn-secondary btn-block" id="add_remove_toggle">&#8595; Add/Remove Components &#8595;</button>
	</div>
	<div class="d-none" id="component_list_container">
		<ul class="list-group d-flex row border border-secondary">
			<li class="removeNodes list-group-item list-group-item-action p-1" id="Disease">Diseases (EFO)</li>
			<li class="removeNodes list-group-item list-group-item-action p-1" id="kegg_Disease">Diseases (KEGG)</li>
			<li class="removeNodes list-group-item list-group-item-action p-1" id="HPO">HPO Terms</li>
			<li class="removeNodes list-group-item list-group-item-action p-1" id="Pathway">Pathways (Reactome)</li>
			<li class="removeNodes list-group-item list-group-item-action p-1" id="kegg_Pathway">Pathways (KEGG)</li>
			<li class="removeNodes list-group-item list-group-item-action p-1" id="Drug">Drugs</li>
			<li class="removeNodes list-group-item list-group-item-action p-1" id="Compound">Compounds (ChEMBL)</li>
			<li class="removeNodes list-group-item list-group-item-action p-1" id="Prediction">Compounds (Predictions)</li>
		</ul>
	</div>
</div>

<div class="mt-2">
	<div class="row">
		<button class="btn btn-sm btn-secondary btn-block" id="network_customization">&#8595; Customize Network Style &#8595;</button>
	</div>
	<div class="d-none" id="network_customization_panel">
		<div class="form-group row mb-0">
			<select class="form-control form-control-sm" name="style_nodeGroup" id="style_nodeGroup">
				<option value="all">Choose Node Type (Default All)</option>
				<option value="Protein">Core genes/proteins</option>
				<option value="Protein_N">Neighbouring genes/proteins</option>
				<option value="Disease">Diseases (EFO)</option>
				<option value="kegg_Disease">Diseases (KEGG)</option>
				<option value="HPO">HPO Terms</option>
				<option value="Pathway">Pathways (Reactome)</option>
				<option value="kegg_Pathway">Pathways (KEGG)</option>
				<option value="Drug">Drugs</option>
				<option value="Compound">Compounds (ChEMBL)</option>
				<option value="Prediction">Compounds (Predictions)</option>
			</select>
		</div>
		<ul class="list-group d-flex row border border-secondary">
			<li class="list-group-item list-group-item-action p-1" id="nodeGroup_NodeSize">
				<div class="btn-group btn-group-sm col-md-12" role="group" aria-label="Change Node Size of Node Type">
					<button type="button" class="customizeStyle btn btn-sm btn-secondary" id="nodeSize_down">-</button>
					<button type="button" class="customizeStyle btn btn-sm" id="fontSize_res">Node Size</button>
					<button type="button" class="customizeStyle btn btn-sm btn-secondary" id="nodeSize_up">+</button>
				</div>
			</li>
			<li class="list-group-item list-group-item-action p-1" id="nodeGroup_fontSize">
				<div class="btn-group btn-group-sm col-md-12" role="group" aria-label="Change Font Size of Node Type">
					<button type="button" class="customizeStyle btn btn-sm btn-secondary" id="fontSize_down">-</button>
					<button type="button" class="customizeStyle btn btn-sm" id="fontSize_res">Font Size</button>
					<button type="button" class="customizeStyle btn btn-sm btn-secondary" id="fontSize_up">+</button>
				</div>
			</li>
			<li class="list-group-item list-group-item-action p-1" id="nodeGroup_labelPosition">
				<div class="btn-group col-md-12" role="group" aria-lab	el="Note Type Label Position">
					<div class="btn-group col-md-12 p-0" role="group">
						<button id="noteTypeLabelPosition" type="button" class="col-md-12 btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Label Position
						</button>
						<div class="dropdown-menu col-md-12" aria-labelledby="noteTypeLabelPosition">
							<button type="button" class="customizeStyle btn btn-sm" id="left">Left</button>
							<button type="button" class="customizeStyle btn btn-sm" id="right">Right</button>
							<button type="button" class="customizeStyle btn btn-sm" id="top">Top</button>
							<button type="button" class="customizeStyle btn btn-sm" id="bottom">Bottom</button>
							<button type="button" class="customizeStyle btn btn-sm" id="center">Center</button>
						</div>
					</div>
				</div>
			</li>
			

			<li class="list-group-item list-group-item-action p-1" id="legend_on_off">
				<div class="btn-group col-md-12" aria-lab	el="Note Edge Legend">
					<div class="btn-group col-md-12 p-0">
						<button id="legend_on_off_btn" type="button" class="col-md-12 btn btn-sm btn-secondary" aria-haspopup="true" aria-expanded="false">
						Node & Edge Legend On/Off
						</button>
					</div>
				</div>
			</li>
			
		</ul>
	</div>
</div>

<div class="p-0 mt-4 text-break">
	<h5 class="row alert alert-secondary p-1 mb-0 pb-2 border-bottom-0 border-secondary" href="#search_parameters"><b>Query Parameters</b></h5>
	<ul class="list-group list-group-flush row p-1 mt-0 border border-secondary" id="search_parameters">
	<li class="list-group-item">Job ID: <b><?=$file_name?></b></li>
	</ul>
</div>

<div class="mt-4">
	<h5 class="row alert alert-secondary mb-0 p-1 pb-2 border-bottom-0 border-secondary mb-1"><b>Download Options</b></h5>
	<div class="row mt-0 p-0 pt-0">
		<div class="col-12 form-group mb-1 mt-1 pt-0">
		<a id="csv_report_file" href="<?php echo $directory_of_job.$file_name.'.csv'; ?>" target=_blank class="btn btn-sm btn-info btn-block">Download KG as a table (CSV)</a>
		</div>
		<div class="col-12 form-group mb-1">
		<a id="data_json_file" href="<?php echo $directory_of_job.$file_name.'.json'; ?>" target=_blank class="btn btn-sm btn-info btn-block">Download KG as a network (JSON)</a>
		</div>
		<div class="col-12 form-group mb-1">
		<a id="report_file" href="<?php echo $directory_of_job.$file_name.'.txt'; ?>" target=_blank class="btn btn-sm btn-info btn-block">Download Query Report</a>
		</div>
	</div>
</div>

<div class="mt-2">
	<h6 class="row">Download KG as an image (PNG)</h6>
	<div class="row">
		<div class="btn-group btn-block" role="group">
			<div class="btn-group btn-group-toggle btn-block d-flex" data-toggle="buttons">
				<label class="btn btn-sm btn-outline-secondary active col-6">
					<input type="radio" name="img_part" value=false autocomplete="off" checked> Visible Part
				</label>
				<label class="btn btn-sm btn-outline-secondary col-6">
					<input type="radio" name="img_part" value=true autocomplete="off"> Fit Whole Graph
				</label>
			</div>
		</div>
	</div>
</div>

<div class="row mt-0">
	<div class="btn-group btn-block" role="group">
		<div class="btn-group btn-group-toggle btn-block d-flex" data-toggle="buttons">
			<label class="btn btn-sm btn-outline-secondary col-6">
				<input type="radio" name="img_resolition" value="1" autocomplete="off"> Normal Resolution
			</label>
			<label class="btn btn-sm btn-outline-secondary active col-6">
				<input type="radio" name="img_resolition" value="2" autocomplete="off" checked> High Resolution (x2)
			</label>
		</div>
	</div>
</div>

<div class="row">
	<div class="btn-group btn-block" role="group">
		<button id="img_export" type="button" class="btn btn-info"  aria-expanded="false">
		Export
		</button>
	</div>
</div>

<div class="form-group row mt-4">
	<a href="index.php" target=_blank class="btn btn-primary btn-block">New Knowledge Graph Query</a>
</div>
