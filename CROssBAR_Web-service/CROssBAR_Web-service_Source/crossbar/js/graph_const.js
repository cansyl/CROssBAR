	var error_msg_info = '<b>Operation terminated.</b> Possible reasons and solutions:</br>';
	
	$( function() {
		$( "#reasons_of_fail" ).tabs({
			event: "mouseover"
		});
	});

	$('#sample_link_1').on('click',function(){
		$('input[name="proteins"]').val('');
		$('input[name="drugs"]').val('trifluoperazine');
		$('input[name="pathways"]').val('');
		$('input[name="diseases"]').val('gastric cancer');
		$('input[name="hpos"]').val('');
		$('input[name="tax_ids_multi"]').val('');
	});
	$('#sample_link_2').on('click',function(){
		$('input[name="proteins"]').val('PTEN');
		$('input[name="drugs"]').val('Sorafenib');
		$('input[name="pathways"]').val('');
		$('input[name="diseases"]').val('');
		$('input[name="hpos"]').val('');
		$('input[name="tax_ids_multi"]').val('');
	});

	function progress_bar( e ) {
		if(e == 'start'){
			$(".progress-bar").addClass('bg-warning');
			$(".progress-bar").addClass('progress-bar-animated');
			$(".progress-bar").removeClass('bg-danger');
		}else if(e == 'error'){
			$(".progress-bar").removeClass('bg-warning');
			$(".progress-bar").removeClass('progress-bar-animated');
			$(".progress-bar").addClass('bg-danger');
			$(".progress-bar").animate({width: "100%"}, 0);
		}else if(e == 'success'){
			// if redirection does not work
			$(".progress-bar").removeClass('bg-warning');
			$(".progress-bar").addClass('bg-success');
			$(".progress-bar").animate({width: "100%"}, 0);
			$('#search_form_area').addClass('d-none');
			$('#network_manipulation').removeClass('d-none');
		}
	}

	$("#searchform").on( "submit", function( event ) {
		event.preventDefault();
		$('#progress_status').text('Validating search parameters...');
		progress_bar('start');
		$.ajax({
			type:"POST",
			url:'input_validation.php',
			data: $(this).serialize(),
			success: function(validate){
				switch(validate){
					case 'Disease Error':
					case 'Pathway Error':
					case 'Drug Error':
					case 'HPO Error':
					case 'Protein Error':
						$('#error_msg').dialog('open');
						progress_bar('error');
					break;

					default:
						$(".progress-bar").animate({
							width: "10%"
						}, 100);
						main_accessions(validate);
				}
			}
		});
	});

	function manuel_start_search(provided_data){
		$('#progress_status').text('Validating search parameters...');
		progress_bar('start');
		$.ajax({
			type:"POST",
			url:'input_validation.php',
			data: provided_data,
			success: function(validate){
				switch(validate){
					case 'Disease Error':
					case 'Pathway Error':
					case 'Drug Error':
					case 'HPO Error':
					case 'Protein Error':
						$('#error_msg').dialog('open');
						progress_bar('error');
					break;
					
					default:
						$(".progress-bar").animate({
							width: "10%"
						}, 100);
						main_accessions(validate);
				}
			}
		});
	}

	function main_accessions(file){
		$('#progress_status').text('Collecting and processing core proteins...');
		cy.elements().remove(); // clean data if exist
		$.ajax({
			type:"POST",
			dataType: "json",
			url:'main_accessions.php',
			data: {params:file},
			success: function(small_network){
				cy.add(small_network);
				var layout = cy.layout({
					name: 'CrossBarLayout',
					fit: 'viewport',
					divideByProperty: "Node_Type",
					orderOfNodeTypes: [1,2,3,4,5,6,7],
					lesslayer: 1
				});
				layout.run();
				$(".progress-bar").animate({
					width: "30%"
				}, 100);
				if ($('#first_neighbours').is(':checked')){
					first_neighbours(file);
				}else{
					disease_drug(file);
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				if(XMLHttpRequest['responseText'] == 'Protein Error' || XMLHttpRequest['responseText'] == 'Protein Fetch Error' || XMLHttpRequest['responseText'] == 'compound error'){
					$('#error_msg').dialog('open');
					progress_bar('error');
				}
			}
		});
	}

	function first_neighbours(file){
		$('#progress_status').text('Collecting and processing neighbouring proteins...');
		$.ajax({
			type:"POST",
			dataType: "json",
			url:'first_neighbours.php',
			data: {params:file},
			success: function(result){
				cy.elements().remove(); // clean data
				cy.add( result );
				$(".progress-bar").animate({
					width: "50%"
				}, 100);
				var layout = cy.layout({
					name: 'concentric',
					fit: 'viewport',
				});
				layout.run();
				disease_drug(file);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$('#error_msg').dialog('open');
				progress_bar('error');
			}
		});
	}

	function disease_drug(file){
		$('#progress_status').html('Collecting and processing diseases and drugs...');
		$.ajax({
			type:"POST",
			dataType: "json",
			url:'disease_drug.php',
			data: {params:file},
			success: function(result){
				cy.elements().remove(); // clean data
				cy.add( result );
				$(".progress-bar").animate({
					width: "70%"
				}, 100);
				var layout = cy.layout({
					name: 'CrossBarLayout',
					fit: 'viewport',
					divideByProperty: "Node_Type",
					orderOfNodeTypes: [1,2,3,4,5,6,7],
					lesslayer: 1
				});
				layout.run();
				compounds_predictions(file);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				$('#error_msg').dialog('open');
				progress_bar('error');
			}
		});
	}

	function compounds_predictions(file){
		$('#progress_status').html('Collecting and processing bioactive and predicted compounds...');
		$.ajax({
			type:"POST",
			dataType: "json",
			url:'compounds_predictions.php',
			data: {params:file},
			success: function(result){
				cy.elements().remove(); // clean data
				cy.add( result );
				$(".progress-bar").animate({
					width: "90%"
				}, 100);
				var layout = cy.layout({
					name: 'CrossBarLayout',
					fit: 'viewport',
					divideByProperty: "Node_Type",
					orderOfNodeTypes: [1,2,3,4,5,6,7],
					lesslayer: 1
				});
				layout.run();
				kegg_relations(file);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				$('#error_msg').dialog('open');
				progress_bar('error');
				console.log(XMLHttpRequest['responseText']);
			}
		});
	}

	function kegg_relations(file){
		$('#progress_status').html('Finalizing the knowledge graph...');
		$.ajax({
			type:"POST",
			dataType: "json",
			url:'kegg_relations.php',
			data: {params:file},
			success: function(result){
				window.location.assign('job.php?id='+file);
				cy.elements().remove(); // clean data
				cy.add( result );
				$('#progress_status').html('Search completed, your job id: <b>'+file+'</b>');
				progress_bar('success');
				var layout = cy.layout({
					name: 'CrossBarLayout',
					fit: 'viewport',
					divideByProperty: "Node_Type",
					orderOfNodeTypes: [1,2,3,4,5,6,7],
					lesslayer: 1
				});
				layout.run();
				// successfully end of search
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				$('#error_msg').dialog('open');
				progress_bar('error');
				//console.log(XMLHttpRequest['responseText']);
			}
		});
	}

    $("#error_msg").dialog({
	  autoOpen: false,
      modal: true,
	  minWidth: 500,
	  dialogClass: "no-close",
      buttons: [{
		  text: "Try Again",
		  click: function() {
			$( this ).dialog( "close" );
		  }
		}]
    });
