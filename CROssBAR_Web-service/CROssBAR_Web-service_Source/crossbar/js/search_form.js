	$('#num_of_nodes_toggle').click(function() {
		if($('#num_of_nodes_panel').hasClass('d-none'))
			$('#num_of_nodes_panel').removeClass('d-none');
		else
			$('#num_of_nodes_panel').addClass('d-none');
	});

	$('#num_of_all_nodes').keyup(function() {
		var numofnodes = $(this).val();
		$('#num_of_fn_nodes').val(numofnodes);
		$('#num_of_pathways').val(numofnodes);
		$('#num_of_phenotypes').val(numofnodes);
		$('#num_of_drugs').val(numofnodes);
		$('#num_of_diseases').val(numofnodes);
		$('#num_of_compounds').val(numofnodes);
	});
	$('#num_of_all_nodes').change(function() {
		var numofnodes = $(this).val();
		$('#num_of_fn_nodes').val(numofnodes);
		$('#num_of_pathways').val(numofnodes);
		$('#num_of_phenotypes').val(numofnodes);
		$('#num_of_drugs').val(numofnodes);
		$('#num_of_diseases').val(numofnodes);
		$('#num_of_compounds').val(numofnodes);
	});
