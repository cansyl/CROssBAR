$( function() {
//$('#network').height( $(document).height() - $('#top_menu').height() - 20);
$('#network').height( screen.height - $('#top_menu').height() - 130 );
$('.selectpicker').selectpicker();

    $( "#orderingComponents" ).sortable();
    $( "#orderingComponents" ).disableSelection();

    $( "#orderingComponents_more" ).sortable();
    $( "#orderingComponents_more" ).disableSelection();

    function split( val ) {
		//console.log(val.split( /,\s*/ ));
      //return val.split( /,\s*/ );
      //console.log(val.split( '|' ));
	  return val.split(  '|' );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }

 $( "#node_search" )
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 1,
        source: function( request, response ) {
			$.ajax( {
			  url: "search_in_network.php",
			  dataType: "json",
			  data: {
				term: request.term,
				file: $('#network_f_name').val(),
			  },
			  success: function( data ) {
				response( data );
			  }
			} );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          //terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
         // terms.push( "" );
          //this.value = terms.join( ' | ' );
          this.value = terms.join( '' );

		  // clicked node
		  //console.log(ui.item.value);
		  apply_high(ui.item.value, $('#network_f_name').val());
          return false;
        }
      });

	function apply_high(name, file){
		$.ajax( {
		  url: "take_id_of_node.php",
		  data: {
			name: name,
			file: file
		  },
		  success: function( data ) {
			//console.log(data);
			//$( "#"+data ).trigger( "click" );
			//cy.$('node[id = "'+data+'"]').eq(0).trigger('tap');
			//cy.$('node[id = "'+data+'"]').trigger('tap');
			//console.log(cy.$('node[id = "'+data+'"]'));
			//var ele = e.target;

			var ele = cy.$('node[id = "'+data+'"]');
			var degree = 
			(ele.addClass('highlight')
				.outgoers()
				.union(ele.incomers())
				.length) / 2;

			$.ajax({
				type:"POST",
				url:'selected_node.php',
				data: {node:ele.data(), degree:degree},
				success: function(result){
					//console.log(result);
					if($('#selected_nodes').hasClass('d-none')){
						$('#selected_nodes').append('<h5 class="row alert alert-secondary p-1 mb-0 border-bottom-0 border-secondary">Selected Nodes</h5>');
						$('#selected_nodes').removeClass('d-none');
					}
					$('#selected_nodes').append(result);
				}
			});
			ele.addClass('zelected');
			ele.connectedEdges().connectedNodes().addClass('highlightednode');
			ele.connectedEdges().animate({
				style: {width: "3px","opacity":"1","font-size": "10px"}
			});
		  }
		} );
	}

    $( ".autocomplete" )
      .on( "keydown", function( event ) {
		 // console.log($('#only_reviewed').val());
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 2,
        source: function( request, response ) {
			$.ajax( {
			  url: "autocomplete.php",
			  dataType: "json",
			  data: {
				term: request.term,
				id: $(this)[0].element[0].id,
				tax: $('#tax_ids_multi').val(),
				rew: $('#only_reviewed').prop('checked')
			  },
			  success: function( data ) {
				//console.log(data);
				response( data );
			  }
			} );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ' | ' );
          return false;
        }
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
				/*
				console.log(validate);
				return 1;
				*/
				switch(validate){
					case 'Disease Error':
					case 'Pathway Error':
					case 'Drug Error':
					case 'HPO Error':
					case 'Protein Error':
						$('#error_msg').html('<b>Operation terminated.</b> No entry found with the given name.<br/>Please choose terms from the suggested list that appear after typing a few letters.');
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
					$('#error_msg').html('<b>Operation terminated.</b> One or more of the query terms could not be found in the database, or the query terms have no relations to be used in the graph construction.<br/>Please choose your query term name from the suggestion list, or re-check your UniProt protein accession(s) / ChEMBL compound id(s), or include additional organisms to your query using the taxonomic filter (the default organism is human).');
					$('#error_msg').dialog('open');
					progress_bar('error');
				}
				console.log(XMLHttpRequest['responseText']);
				//console.log(textStatus);
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
				$('#error_msg').html('Operation terminated. Error occurred while collecting and processing <b>first neighbours</b>!<br/>We will fix the issue soon.');
				$('#error_msg').dialog('open');
				progress_bar('error');
				console.log(XMLHttpRequest['responseText']);
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
				$('#error_msg').html('Operation terminated. Error occurred while collecting and processing <b>disease and drugs</b>!<br/>We will fix the issue soon.');
				$('#error_msg').dialog('open');
				progress_bar('error');
				console.log(XMLHttpRequest['responseText']);
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
				$('#error_msg').html('Operation terminated. Error occurred while collecting and processing  bioactive and predicted compounds!<br/>We will fix the issue soon.');
				$('#error_msg').dialog('open');
				progress_bar('error');
				console.log(XMLHttpRequest['responseText']);
			}
		});
	}

	function kegg_relations(file){
		//console.log(file);
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
				$('#csv_report_file').href= 'datas/'+file+'.csv';
				$('#data_json_file').href= 'datas/'+file+'.json';
				$('#report_file').href= 'datas/'+file+'.txt';
				progress_bar('success');
				var layout = cy.layout({
					name: 'CrossBarLayout',
					fit: 'viewport',
					divideByProperty: "Node_Type",
					orderOfNodeTypes: [1,2,3,4,5,6,7],
					lesslayer: 1
				});
				layout.run();

				result.search.forEach((s) => {
					for (var p in s) {
						$('#search_parameters').append( '<li class="list-group-item">'+p+': '+s[p]+'</li><hr/>' );
					}
				});
				$('#search_parameters').append( '<li class="list-group-item">Number of Nodes: '+result.options.num_of_nodes+'</li><hr/>' );
				var fn_stat = 'no';
				if(result.options.fn)
					fn_stat = 'yes';
				$('#search_parameters').append( '<li class="list-group-item">Include interacting proteins: '+fn_stat+'</li><hr/>' );
				var rw = 'no';
				if(result.options.reviewed_filter)
					rw = 'yes';
				$('#search_parameters').append( '<li class="list-group-item">Only reviewed genes/proteins: '+rw+'</li><hr/>' );
				separator = ',';
				$('#search_parameters').append( '<li class="list-group-item">Included tax Id(s): '+result.options.tax_ids.join(separator)+'</li>' );

				// successfully end of search
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				$('#error_msg').html('Operation terminated. Error occurred while defining <b>KEGG relations</b>!<br/>We will fix the issue soon.');
				$('#error_msg').dialog('open');
				progress_bar('error');
				console.log(XMLHttpRequest['responseText']);
			}
		});
	}

	function error_report(file,hata){
		$.ajax({
			type:"POST",
			url:'errors.php',
			data: {params:file,error:hata}
		});
	}

    $("#error_msg").dialog({
	  autoOpen: false,
      modal: true,
	  dialogClass: "no-close",
      buttons: [{
		  text: "Try Again",
		  click: function() {
			$( this ).dialog( "close" );
		  }
		}]
    });

	cy.on('tap', function(event){
		var evtTarget = event.target;
		if( evtTarget === cy ){
			$('#selected_nodes').html('');
			$('#selected_nodes').addClass('d-none');
			// if clicked on the empty area (canvas)
			cy.$('node').removeClass('highlightednode');
			cy.$('node').removeClass('zelected');
			cy.$('edge').forEach((ed) => {
				//ed.removeClass('highlightededge');
				ed.animate({
					style: {width: "1px","opacity":"0.25","font-size": "0px"}
				});
			});
		}else{
			if(evtTarget.hasClass('zelected')){
				
			}else{

			}
		}
	});

	cy.on('select', function(e){
		var ele = e.target;
		var degree = 
		(ele.outgoers()
			.union(ele.incomers())
			.length) / 2;
		degree = Math.floor(degree);
		$.ajax({
			type:"POST",
			url:'selected_node.php',
			data: {node:ele.data(), degree:degree},
			success: function(result){
				//console.log(result);
				if($('#selected_nodes').hasClass('d-none')){
					$('#selected_nodes').append('<h5 class="row alert alert-secondary p-1 mb-0 border-bottom-0 border-secondary">Selected Nodes</h5>');
					$('#selected_nodes').removeClass('d-none');
				}
				$('#selected_nodes').append(result);
			}
		});

		ele.addClass('zelected');
		ele.connectedEdges().connectedNodes().addClass('highlightednode');

		ele.connectedEdges().animate({
			style: {width: "3px","opacity":"1","font-size": "10px"}
		});
		
	});

	cy.on('mouseover', 'node', function(e) {
		var ele = e.target;
		cy.elements()
			.difference(ele.outgoers()
				.union(ele.incomers()))
			.not(ele)
			.addClass('semitransp');
		ele.addClass('highlight')
			.outgoers()
			.union(ele.incomers())
			.addClass('highlight');
	});
	cy.on('mouseout', 'node', function(e) {
		var ele = e.target;
		cy.elements()
			.removeClass('semitransp');
		ele.removeClass('highlight')
			.outgoers()
			.union(ele.incomers())
			.removeClass('highlight');
	});

 } );
