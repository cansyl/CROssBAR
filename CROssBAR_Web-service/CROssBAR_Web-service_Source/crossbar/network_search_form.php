	<h5 class="row">Knowledge Graph Query</h5>
	<h6 class="row"><small>Please enter terms of interest (multiple terms are separated by pipe - |)</small></h6>
	<hr class="row mt-0"/>
	<form method="post" id="searchform">
		<div class="form-group row">
			<span class="pb-2"><i><span id="example_query_link">Example Search: </span>
				<a href="#1" id="sample_link_1">#1</a>, 
				<a href="#2" id="sample_link_2">#2</a></i>
			</span>
			<input type="text" name="proteins" id="protein_input" placeholder="Gene name(s) / Protein UniProt acc." class="form-control form-control-sm autocomplete"/>
		</div>

		<div class="form-group row">
			<div class="input-group input-group-sm">
				<input type="text" name="drugs" id="drug_input" placeholder="Drug name(s) / DrugBank id(s) / ChEMBL id(s)" class="form-control form-control-sm autocomplete"/>
			</div>
		</div>

		<div class="form-group row">
			<input type="text" name="pathways" id="pathway_input" placeholder="Pathway name(s)" class="form-control form-control-sm autocomplete"/>
		</div>

		<div class="form-group row">
			<input type="text" name="diseases" id="disease_input" placeholder="Disease name(s)" class="form-control form-control-sm autocomplete"/>
		</div>

		<div class="form-group row">
			<input type="text" name="hpos" id="hpo_input" placeholder="HPO term name(s)" class="form-control form-control-sm autocomplete"/>
		</div>

		<div class="form-group row">
			<select title="Tax id(s) for genes (default: human)" multiple id="tax_ids_multi" name="tax_ids_multi[]" data-style="bg-white shadow-sm " class="selectpicker w-100">
				<option value=9606>9606 (Human)</option>
				<option value=10116>10116 (Rat)</option>
				<option value=9823>9823 (Pig)</option>
				<option value=9913>9913 (Bovine)</option>
				<option value=10090>10090 (Mouse)</option>
				<option value=559292>559292 (Baker's yeast)</option>
				<option value=9986>9986 (Rabbit)</option>
				<option value=83332>83332 (MYCTU)</option>
				<option value=83333>83333 (Ecoli)</option>
				<option value=694009>694009 (SARS-CoV)</option>
				<option value=2697049>2697049 (SARS-CoV-2)</option>
			</select>
		</div>

	<div class="row">
		<a class="btn btn-sm btn-info btn-block m-0" id="num_of_nodes_toggle" style="color:white">&#8595; # of nodes (default: n=10 for all, max:100) &#8595;</a>
	</div>

	<div class="d-none mt-0" id="num_of_nodes_panel">
		<div class="row mt-0 p-0">
			<span class="col-md-9 border-bottom text-truncate bg-secondary pl-1 text-white"><small>Change default value (n) for all</small></span>
			<input type="number" name="num_of_fn_nodes" id="num_of_all_nodes" min=1 max=100 class="form-control form-control-sm mt-0 m-0 col-md-3" placeholder="10"/>
		</div>
		<div class="row mt-0 p-0">
			<span class="col-md-9 border-bottom text-truncate bg-secondary pl-1 text-white"><small># of neighbouring genes/proteins</small></span>
			<input type="number" name="num_of_fn_nodes" id="num_of_fn_nodes" min=1 max=100 class="form-control form-control-sm mt-0 m-0 col-md-3" placeholder="10"/>
		</div>
		<div class="row mt-0 p-0">
			<span class="col-md-9 border-bottom text-truncate bg-secondary pl-1 text-white"><small># of pathways</small></span>
			<input type="number" name="num_of_pathways" id="num_of_pathways" min=1 max=100 class="form-control form-control-sm m-0 col-md-3" placeholder="10"/>
		</div>
		<div class="row mt-0 p-0">
			<span class="col-md-9 border-bottom text-truncate bg-secondary pl-1 text-white"><small># of phenotypes</small></span>
			<input type="number" name="num_of_phenotypes" id="num_of_phenotypes" min=1 max=100 class="form-control form-control-sm m-0 col-md-3" placeholder="10"/>
		</div>
		<div class="row mt-0 p-0">
			<span class="col-md-9 border-bottom text-truncate bg-secondary pl-1 text-white"><small># of drugs</small></span>
			<input type="number" name="num_of_drugs" id="num_of_drugs" min=1 max=100 class="form-control form-control-sm m-0 col-md-3" placeholder="10"/>
		</div>
		<div class="row mt-0 p-0">
			<span class="col-md-9 border-bottom text-truncate bg-secondary pl-1 text-white"><small># of diseases</small></span>
			<input type="number" name="num_of_diseases" id="num_of_diseases" min=1 max=100 class="form-control form-control-sm m-0 col-md-3" placeholder="10"/>
		</div>
		<div class="row mt-0 p-0">
			<span class="col-md-9 border-bottom text-truncate bg-secondary pl-1 text-white"><small># of compounds</small></span>
			<input type="number" name="num_of_compounds" id="num_of_compounds" min=1 max=100 class="form-control form-control-sm m-0 col-md-3" placeholder="10"/>
		</div>
	</div>
	<small id="num_of_nodes" class="form-text text-muted pb-2">
	The most overrepresented n terms from each biological component will be incorporated to the graph.
	</small>
		
		
		<div class="mb-0 mt-0 form-group form-group-sm row">
			<label class="btn btn-sm btn-info btn-block">
				<input type="checkbox" checked name="first_neighbours" id="first_neighbours" class="float-left mt-1"> Include interacting genes/proteins
			</label>
			<label class="mt-0 btn btn-sm btn-info btn-block">
				<input type="checkbox" checked name="only_reviewed" id="only_reviewed" class="float-left mt-1"> Include only reviewed gene/protein entries (Swiss-Prot)
			</label>

			<label class="mt-0 btn btn-sm btn-info btn-block">
				<input type="checkbox" checked name="chembl_compounds" id="chembl_compounds" class="float-left mt-1"> Include ChEMBL bioactivities
			</label>
			<label class="mt-0 btn btn-sm btn-info btn-block">
				<input type="checkbox" checked name="predictions" id="predictions" class="float-left mt-1"> Include predicted bioactivities
			</label>
		</div>
		<div class="mt-0 form-group row">
			<button type="submit" class="btn btn-primary btn-block font-weight-bold">Construct Knowledge Graph</button>
		</div>
	</form>
