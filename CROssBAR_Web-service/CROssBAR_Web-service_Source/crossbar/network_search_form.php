	<h5 class="row">Knowledge Graph Query</h5>
	<h6 class="row"><small>Please enter terms of interest (multiple terms are separated by pipe - |)</small></h6>
	<hr class="row mt-0"/>
	<form method="post" id="searchform">
		<div class="form-group row">
			<input type="text" name="proteins" id="protein_input" placeholder="Gene name(s) / Protein UniProt acc." class="form-control form-control-sm autocomplete"/>
		</div>

		<div class="form-group row">
			<div class="input-group input-group-sm">
				<input type="text" name="drugs" id="drug_input" placeholder="Drug name(s) / ChEMBL id(s)" class="form-control form-control-sm autocomplete"/>
				<!--
				<div class="input-group-append">
					<label class="btn btn-info">
					<input type="checkbox" checked name="chembl"/> Include ChEMBL
					</label>
				</div>
				-->
			</div>
		</div>

		<!--
		<div class="form-group row">
			<input type="text" name="compounds" id="compound_input" placeholder="Chembl Compound Id(s)" class="form-control form-control-sm" aria-describedby="compound_input">
		</div>
		-->

		<div class="form-group row">
			<input type="text" name="pathways" id="pathway_input" placeholder="Pathway name(s)" class="form-control form-control-sm autocomplete">
		</div>

		<div class="form-group row">
			<input type="text" name="diseases" id="disease_input" placeholder="Disease name(s)" class="form-control form-control-sm autocomplete">
		</div>

		<div class="form-group row">
			<input type="text" name="hpos" id="hpo_input" placeholder="HPO term name(s)" class="form-control form-control-sm autocomplete">
		</div>
		<!--
		<div class="form-group row">
			<input type="text" name="tax_ids" id="tax_ids" placeholder="Taxonomi Id(s)" class="form-control form-control-sm" aria-describedby="tax_ids">
			<small id="tax_ids" class="form-text text-muted">
			Default: 9606 (HUMAN). Other possible values: 10116 | 9823 | 9913 | 10090 | 559292 | 9986 | 83332 | 83333
			</small>
		</div>
		-->
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
	</select>
	<!--<input type="hidden" name="tax_ids" id="tax_ids"/>-->
</div>

		<div class="form-group row">
			<input type="number" name="num_of_nodes" id="num_of_nodes" min=1 max=50 class="form-control form-control-sm" placeholder="# of nodes (default: n=10)">
			<small id="num_of_nodes" class="form-text text-muted">
			The most overrepresented n terms from each biological component will be incorporated to the Knowledge Graph
			</small>
		</div>
		<div class="mb-0 mt-0 form-group form-group-sm row">
			<label class="btn btn-sm btn-info btn-block">
				<input type="checkbox" checked name="first_neighbours" id="first_neighbours" class="float-left mt-1"> Include interacting genes/proteins
			</label>
			<label class="mt-0 btn btn-sm btn-info btn-block">
				<input type="checkbox" checked name="only_reviewed" id="only_reviewed" class="float-left mt-1"> Only reviewed gene/protein entries
			</label>
		</div>
		<div class="mt-0 form-group row">
			<button type="submit" class="btn btn-primary btn-block font-weight-bold">Construct Knowledge Graph</button>
		</div>
	</form>
