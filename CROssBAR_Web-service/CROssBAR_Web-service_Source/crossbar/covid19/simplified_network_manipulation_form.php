<div id="selected_edges" class="d-none mb-0"></div>
<div id="selected_nodes" class="d-none mb-2"></div>

<h5 class="row alert alert-secondary p-1 mb-0 border-bottom-0 border-secondary">
Node Search in Network
</h5>
<div class="row border pl-4 pr-4 pt-0 mt-0 mb-2" style="padding-top: 15px !important; padding-bottom: -5px !important;">
	<div class="form-group" style="width: 100% !important;">
	<input class="form-control form-control-sm" name="node_search" id="node_search" placeholder="Type your node to be searched in network"/>
	</div>
</div>

<div class="mt-2 mb-2 p-0 row">
<div class="col-md-12">
<img src="covid19/CROssBAR_KG_COVID19_Legend.png" class="img-fluid" id="canvas_bg_item"/>
</div>
</div>

<h5 class="row alert alert-secondary p-1 mb-0 border-bottom-0 border-secondary">Graph Customization</h5>
<div class="mt-2">
<h5 class="row">Add/remove components:</h5>
<div class="row">
	<button class="btn btn-sm btn-secondary btn-block" id="add_remove_toggle">&#8595; Component List &#8595;</button>
</div>
<div class="d-none" id="component_list_container">

<ul class="list-group d-flex row border border-secondary">
	<li class="removeNodes list-group-item list-group-item-action p-1" id="protein">protein</li>
	<li class="removeNodes list-group-item list-group-item-action p-1" id="reactome pathway">reactome pathway</li>
	<li class="removeNodes list-group-item list-group-item-action p-1" id="kegg pathway">kegg pathway</li>
	<li class="removeNodes list-group-item list-group-item-action p-1" id="kegg disease">kegg disease</li>
	<li class="removeNodes list-group-item list-group-item-action p-1" id="efo disease">efo disease</li>
	<li class="removeNodes list-group-item list-group-item-action p-1" id="hpo">hpo</li>
	<li class="removeNodes list-group-item list-group-item-action p-1" id="small molecule drug">small molecule drug</li>
	<li class="removeNodes list-group-item list-group-item-action p-1" id="biotech drug">biotech drug</li>
	<li class="removeNodes list-group-item list-group-item-action p-1" id="biotech drug - vaccine">biotech drug - vaccine</li>
	<li class="removeNodes list-group-item list-group-item-action p-1" id="biotech drug - plasma">biotech drug - plasma</li>
	<li class="removeNodes list-group-item list-group-item-action p-1" id="biotech drug - vector">biotech drug - vector</li>
	<li class="removeNodes list-group-item list-group-item-action p-1" id="biotech drug - oligonucleotide">biotech drug - oligonucleotide</li>
	<li class="removeNodes list-group-item list-group-item-action p-1" id="compound">compound</li>
	<li class="removeNodes list-group-item list-group-item-action p-1" id="organism">organism</li>
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
				<option value="protein">protein</option>
				<option value="reactome pathway">reactome pathway</option>
				<option value="kegg pathway">kegg pathway</option>
				<option value="kegg disease">kegg disease</option>
				<option value="efo disease">efo disease</option>
				<option value="hpo">hpo</option>
				<option value="small molecule drug">small molecule drug</option>
				<option value="biotech drug">biotech drug</option>
				<option value="biotech drug - vaccine">biotech drug - vaccine</option>
				<option value="biotech drug - plasma">biotech drug - plasma</option>
				<option value="biotech drug - vector">biotech drug - vector</option>
				<option value="biotech drug - oligonucleotide">biotech drug - oligonucleotide</option>
				<option value="compound">compound</option>
				<option value="organism">organism</option>
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
		</ul>
	</div>
</div>

<div class="p-0 mt-2">
	<h5 class="row alert alert-secondary p-1 mb-0 border-bottom-0 border-secondary" href="#search_parameters">Query Parameters</h5>
	<ul class="list-group list-group-flush row p-1 mt-0 border border-secondary" id="search_parameters">
	<li class="list-group-item">Job ID: <b>Covid-19 Simplified Version</b></li>
	</ul>
</div>

<div class="mt-2">
	<h5 class="row alert alert-secondary mb-0 p-1 pb-0 border-bottom-0 border-secondary mb-1">Download Options</h5>
	<div class="row border border-secondary mt-0 p-0">
		<div class="col-12 form-group mb-1">
		<a id="data_json_file" href="covid19/simplified_covid19_network_data.json" target=_blank class="btn btn-sm btn-info btn-block">Download KG as a network (JSON)</a>
		</div>
	</div>
</div>

<div class="mt-1">
<h5 class="row alert alert-secondary mb-0 p-1 pb-0 border-bottom-0 border-secondary mb-1">Download KG as an image (PNG)</h5>
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

<div class="row mt-1">
	<div class="btn-group btn-block" role="group">
		<div class="btn-group btn-group-toggle btn-block d-flex" data-toggle="buttons">
			<label class="btn btn-sm btn-outline-secondary active col-6">
				<input type="radio" name="img_resolition" value="1" autocomplete="off" checked> Normal Resolution
			</label>
			<label class="btn btn-sm btn-outline-secondary col-6">
				<input type="radio" name="img_resolition" value="2" autocomplete="off"> High Resolution (x2)
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

<div class="form-group row mt-2">
<a href="index.php" class="btn btn-primary btn-block">New Knowledge Graph Query</a>
</div>
